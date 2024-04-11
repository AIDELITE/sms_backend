<?php
defined('BASEPATH') or exit('No direct script access allowed');

use AfricasTalking\SDK\AfricasTalking;
use Instasent\SMSCounter\SMSCounter;
use GuzzleHttp\Client; 

class Sms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->library("check_auth");
        // $this->load->library("beyonic_transactions");
        $this->load->model('user_model');
        $this->load->model('transaction_model');
        $this->load->model('message_model');
        $this->load->model('auth_model');
        $this->load->model('contact_model');
        $this->load->model('sms_provider_model');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
    }

    public function send()
    {
        // set all responses as json
        header("Content-Type:application/json");

        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];

            return $this->output->set_output(json_encode(($feedback)));
        }

        $auth_user = (array) $this->check_auth->get_user();
        $this->user_id = $auth_user['id'];

        $this->user = $this->get_user(intval($this->user_id));
        $this->sms_provider = $this->sms_provider_model->get($this->user['sms_provider_id']);
        // Set your app credentials
        $username = $this->sms_provider['auth_username'];
        $apiKey = $this->sms_provider['auth_api_token'];


        // Initialize the SDK
        $AT = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $this->sms = $AT->sms();


        $this->load->library('form_validation');

        //pickes sent data from the api
        $post_data = json_decode(file_get_contents('php://input'), true);

        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        if (!isset($post_data['contact_id'])) {
            $this->form_validation->set_rules('recipients', 'Recipients ', 'required');
        }


        $this->form_validation->set_rules('message', 'Message', 'required');
        //$this->form_validation->set_rules('senderid', 'Sender ID', 'required');

        $feedback['error'] = true;

        // echo json_encode($post_data);die;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            // Verif Contact ID if provided
            $contact = false;
            if (isset($post_data['contact_id'])) {
                $contact = $this->contact_model->get_contact($post_data['contact_id']);
                if (!$contact) {
                    $feedback = [
                        'message' => 'Provided Contact ID does not exist',
                        'status' => false,
                        'success' => false,
                        'error' => true
                    ];
                    //http_response_code(400);
                    echo json_encode($feedback);
                    return;
                }

                $post_data['recipients'] = $contact['phone_numbers'];
            }
            // send sms from here
            /*
            2. check if acc_balance is enough and deduct cost
            3. update messages and transaction table
            4. send sms
            */
            $user_id = $this->user_id;
            $user = $this->user;
            $balance = $this->transaction_model->accountBalance($user_id);

            $sms_rate = $user['sms_rate'];
            $acc_balance = $balance['acc_balance'];

            # Remove spaces and newLines
            $string = preg_replace('/[\r\n]+/', "", $post_data['recipients']);
            $post_data['recipients'] = preg_replace('/[ \t]+/', "", $string);

            $receipts_array1 = explode(",", $post_data['recipients']);
            $clean_receipts_array = [];

            foreach ($receipts_array1 as $key => $value) {
                # Remove empty numbers
                if (!empty($value)) {
                    if ($this->valid_phone_ug($value) == FALSE) {
                        $invalid_numbers[] = $value;
                    } else {

                        $clean_receipts_array[] = $this->format_contact($value);
                    }
                }
            }

            $receipts_array = $clean_receipts_array;

            ## Validate Each phone numbers

            $no_of_receipts = count($receipts_array);

            $smsCounter = new SMSCounter();
            $result = (array) $smsCounter->count($post_data['message']);

            $sending_charges = $no_of_receipts * $sms_rate * $result['messages'];
            if (empty($invalid_numbers)) {
                if ($acc_balance < $sending_charges) {
                    // Donot send
                    $feedback = [
                        'status' => false,
                        'error' => true,
                        'success' => false,
                        'message' => 'Your account balance is Low'
                    ];
                    //http_response_code(403);
                    echo json_encode($feedback);
                    return;
                } else {
                  $this->db->trans_begin();
                  $eachSmsCharge = $sms_rate*$result['messages'];
                  foreach ($receipts_array as $key => $value) {
                    // Update transactions table
                    $trans_data = [
                        'date_created' => date("Y-m-d H:i:s"),
                        'type' => 'DEBIT',
                        'phone_number' => $value,
                        'DEBIT' => $eachSmsCharge,
                        'narrative' => 'SMS CHARGES',
                        'user_id' => $user_id
                    ];
                    $response = $this->transaction_model->create($trans_data);
                    $transaction_id = $response;
                    // Update Messages Table
                    $message_data = [
                        'transaction_id' => $transaction_id,
                        'recipients' => $value,
                        'contact_id' => NULL,
                        'text' => $post_data['message'],
                        'status' => "Pending",
                        'user_id' => $user_id
                    ];
                    $this->message_model->create($message_data);
                  }
                  if ($this->db->trans_status() === TRUE) {
                      $this->db->trans_commit();
                    // sending sms
                    try {
                        //Thats it, hit send and we'll take care of the rest
                        $receipients = implode(",", $receipts_array);

                        $data = array(
                            'to' => $receipients,
                            'message' => $post_data['message']
                        );

                        if (!empty($post_data['from'])) {
                            $data['from'] = $post_data['from'];
                        }
                        // send
                        //$result['status'] = "Message Sent!";
                        $result = $this->sms->send($data);

                        $update_array = $result['data']->SMSMessageData->Recipients;
                        $this->message_model->update($update_array);
                        $this->transaction_model->update($update_array);
                            $feedback = [
                                'message' => $result['status'],
                                'cost' => $sending_charges,
                                'status' => true,
                                'success' => true
                            ];
                        http_response_code(200);
                    } catch (Exception $e) {
                        $feedback['message'] = $e->getMessage();
                        $feedback['status'] = false;
                        $feedback['success'] = false;
                        $feedback['error'] = true;

                        $this->db->trans_rollback();
                        //http_response_code(500);
                        echo json_encode($feedback);

                        return;
                    }

                  }else {

                          $this->db->trans_rollback();
                          $feedback = [
                              'message' => 'Something went wrong',
                              'status' => false,
                              'success' => false,
                              'error' => true
                          ];

                  }


                }
            } else {
                $invalid_numbers_result = implode(",", $invalid_numbers);
                $feedback = [
                    'message' => 'Incorrect Phone Number Format! Numbers must start with +256 or 0 and must have the correct length of Ugandan Phone Numbers. Invalid Numbers =>[ ' . $invalid_numbers_result . ' ]',
                    'status' => false,
                    'success' => false,
                    'error' => true
                ];
            }
        }

        echo json_encode($feedback);
    }

    private function get_user($id)
    {
        $user = $this->auth_model->get_user($id);
        return $user;
    }
    public function valid_phone_ug($phone_no)
    {
        if (preg_match('/^(0|\+256)[2347]([0-9]{8})$/', $phone_no)) {
            return TRUE;
        }
        return FALSE;
    }

    private function format_contact($contact)
    {

        if (preg_match("/^[\+]+[0-9]{12,12}$/", $contact)) {
            $mobile_number = $contact;
        } elseif (preg_match("/^[07]+[0-9]{9,10}$/", $contact)) {
            $mobile_number = '+256' . substr($contact, -9);
        } else {
            $mobile_number = "";
        }

        return $mobile_number;
    }
}
