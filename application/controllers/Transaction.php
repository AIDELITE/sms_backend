<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

use GuzzleHttp\Client;

class Transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->library("check_auth");
        $this->load->library("beyonic_transactions");
        $this->load->model('user_model');
        $this->load->model('auth_model');
        $this->load->model('transaction_model');
        $this->load->model('utils_model');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = (array) $this->check_auth->get_user();
        $this->active_payment_provider['id'] = 10;//$this->utils_model->get_payment_provider();


        // set all responses as json
        header("Content-Type:application/json");

        if (!$this->isAuthenticated) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }
    }

    public function index()
    {
        if (!$this->user || $this->user['role'] != 1) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $data['transactions'] = $this->transaction_model->get();
        echo json_encode($data);
    }

    # Get User Transactions
    public function user()
    {
        $data['data'] = $this->transaction_model->get("user_id = '{$this->user['id']}'");
        echo json_encode($data);
    }

    //added this line to handle all incomes

    public function allIncomes()
    {
        $post_data = json_decode(file_get_contents('php://input'), true);
        // if($post_data->data_type)
        // {

        // }
        //$data['data'] = $this->transaction_model->getall();
        echo json_encode($post_data);
    }
    //upto here

    public function deposit()
    {

        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        // if ($this->active_payment_provider['id'] == 2) {
        //     $this->form_validation->set_rules('provider', 'Provider', 'required');
        // }
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');

        $feedback['error'] = true;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {

            if (empty($this->active_payment_provider)) {
                $feedback['message'] = "Deposits Not accepted at the moment. Please contact support team";
                http_response_code(500);
            } else {

                # Record transaction
                $this->db->trans_begin();

                $user = (array) $this->check_auth->get_user();
                if (isset($post_data['id']) && is_numeric($post_data['id'])) {
                   $user_id = $post_data['id']; 
                }else{
                   $user_id = $user['id'];
                }
                $post_data['user_id'] = $user_id;
                if ($this->active_payment_provider['id'] == 10) {

                    $data = [
                        'date_created' => date("Y-m-d H:i:s"),
                        'type' => "CREDIT",
                        'status' => 1,
                        'phone_number' => '+256000000000',
                        'CREDIT' => $post_data['amount'],
                        'user_id' => $user_id,
                        'narrative' => "DEPOSIT",
                    ];

                    $insert_id = $this->transaction_model->create($data);

                    if ($insert_id) {
                        $feedback = [
                            'message' => 'Deposit Successful!',
                            'success' => true,
                            'status' => true
                        ];
                        http_response_code(200);
                    }
                } else if ($this->active_payment_provider['id'] == 1) {

                    $result = $this->beyonic_transactions->new_collection($post_data);

                    $data = [
                        'date_created' => date("Y-m-d H:i:s"),
                        'request_id' => $result->id,
                        'organization' => $result->organization,
                        'amount' => $result->amount,
                        'phone_number' => $result->phonenumber,
                        'currency' => $result->currency,
                        'user_id' => $result->metadata->user_id,
                        'status' => $result->status,
                        'state_id' => 1,
                        'payment_provider_id' => 1,
                    ];

                    $insert_id = $this->transaction_model->create_deposit_request($data);

                    if ($insert_id) {
                        $feedback = [
                            'message' => 'Deposit pending user approval',
                            'status' => true
                        ];
                        http_response_code(200);
                    }
                } else if ($this->active_payment_provider['id'] == 2) {
                    $ref = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
                    $ext_ref = join("", explode('-', $ref));
                    $mobile_number = explode("+", $this->format_contact($post_data['phone_number']))[1];
                    $user_data = $this->auth_model->get_user($user_id);

                    $sentepay_data = [
                        'currency' => 'UGX',
                        'provider' => $post_data['provider'],
                        'amount' => $post_data['amount'],
                        'msisdn' => $mobile_number,
                        'narrative' => 'uccfstext Top up',
                        'ext_ref' => $ext_ref,
                        'callback_url' => 'https://api.uccfstext.com/transactions/sentepay_callback',
                        'customer_names' => $user_data['firstname'] . " " . $user_data['lastname'],
                        'customer_email' => $user_data['email']
                    ];

                    try {
                        $res = (array) $this->deposit_with_sentepay($sentepay_data);
                        if (!isset($res['refNo'])) {
                            $feedback = [
                                'message' => 'Something went wrong. Please contact the support team',
                                'status' => false
                            ];
                            http_response_code(500);
                        } else {
                            $deposit_request_data = [
                                'date_created' => date("Y-m-d H:i:s"),
                                'amount' => $sentepay_data['amount'],
                                'phone_number' => $sentepay_data['msisdn'],
                                'currency' => $sentepay_data['currency'],
                                'user_id' => $user_id,
                                'status' => 'pending',
                                'state_id' => 1,
                                'payment_provider_id' => 2,
                            ];
                            $insert_id = $this->transaction_model->create_deposit_request($deposit_request_data);

                            if ($insert_id) {
                                $feedback = [
                                    'message' => 'Deposit pending user approval',
                                    'status' => true
                                ];
                                http_response_code(200);
                            }
                        }
                    } catch (Exception $e) {
                        $feedback = [
                            'message' => 'Something went wrong. Please contact the support teaml',
                            'status' => false
                        ];
                        http_response_code(500);
                    }
                }
            }
        }

        if (isset($feedback['error']) && $feedback['error'] == true) {
            $this->output->set_status_header(400);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $feedback = [
                'message' => 'Something went wrong',
                'status' => false,
                'error' => true
            ];
            http_response_code(500);
        } else {
            $this->db->trans_commit();
        }

        echo json_encode($feedback);
    }

    public function refresh_acc_bal()
    {
        $this->load->helper('refresh_bal_helper');

        refresh_bal(true);
    }


    private function deposit_with_sentepay($data)
    {
        $headers = [
            'X-Authorization' => '',
        ];

        $url = '{{baseUrl}}/transact/collect';
        $client = new Client(['headers' => $headers]);

        $response = $client->post($url, [
            'form_params' => $data,
        ]);

        $body = $response->getBody()->getContents();
        $arr_body = json_decode($body);

        return (array) $arr_body;
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

    public function sentepay_callback_url()
    {
        # code...
    }

    
}
