<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->model('auth_model');
        $this->load->model('user_model');
        $this->load->model('transaction_model');
        $this->load->library('check_auth');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = (array) $this->check_auth->get_user();

        // set all responses as json
        header("Content-Type:application/json");

    }

    public function apiKey()
    {
        $userId=$this->input->post('user_id');
        if (!$this->is_valid_jwt) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'success' => false,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $response = $this->auth_model->get_new_api_key($userId);

        echo json_encode($response);
    }

    public function signup()
    {
        $this->load->library('form_validation');
        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }
        $this->form_validation->set_rules('firstname', 'Firstname', 'required');
        $this->form_validation->set_rules('lastname', 'Lastname', 'required');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $feedback['error'] = true;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            $result = $this->user_model->create($post_data);
            if (is_numeric(($result))) {
                unset($feedback['error']);
                $feedback['status'] = true;
                $feedback['message'] = 'You have successfully signed up. You can now login';
                $feedback['id'] = $result;
                http_response_code(200);
            } else {
                http_response_code(400);
                $feedback = $result;
            }
        }
        echo json_encode($feedback);
    }

    public function login()
    {
        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        $feedback['error'] = true;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
        } else {
            $result = $this->auth_model->login($post_data);
            $feedback = $result;

            if (isset($result['user'])) {

                $user = $result['user'];
                $payload = array(
                    "iss" => "gmt_sms_platform",
                    "iat" => strtotime('now'),
                    "exp" => strtotime('+ 24 hours'),
                    "user" => $user
                );

                $jwt = $this->jwt_lib->get_jwt_token($payload);

                header("Authorization:{$jwt}");
                header("Content-Type:application/json");

                $feedback['auth_token'] = $jwt;
            } else {

            }
        }

        echo json_encode($feedback);
    }

    public function verify_jwt()
    {
        $this->load->helper('refresh_bal_helper');

        if (!$this->is_valid_jwt) {
            $this->output->set_status_header(400);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Invalid Jwt'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        //refresh_bal();

        $user = $this->auth_model->get_user($this->user['id']);
        if (!empty($user)) {
            $this->output->set_status_header(200);
            $feedback = [
                'status' => true,
                'message' => 'Jwt is valid',
                'isValid' => true,
                'user' => $user
            ];
        } else {
            $this->output->set_status_header(403);
            $feedback = [
                'status' => false,
                'message' => 'Invalid Jwt',
                'isValid' => false,
                'user' => $user
            ];
        }

        return $this->output->set_output(json_encode(($feedback)));
    }


    /* private function refresh_acc_bal()
    {
        $this->load->library("beyonic_transactions");

        $this->db->trans_begin();

        $pending = $this->transaction_model->get_pending_deposits();
        $request_ids = [];
        $collection_requests = [];
        $feedback = [];

        foreach ($pending as $key => $value) {
            $request_ids[] = $value['request_id'];
        }

        if (count($request_ids) > 0) {
            foreach ($request_ids as $value) {
                $collection_requests[] = $this->beyonic_transactions->get_one_collection_request($value);
            }
        }

        foreach ($collection_requests as $request) {
            //$request->status = 'successful';
            if ($request->status == 'successful') {
                # update transactions table and user acc balance
                $data = ['state_id' => 2, 'status' => 'successful'];
                $this->transaction_model->update_deposit_request($request->id, $data);

                $trans_data = [
                    'date_created' => date('Y-m-d'),
                    'type' => 'DEPOSIT',
                    'amount' => $request->amount,
                    'narrative' => 'Mobile Money Deposit',
                    'user_id' => $request->metadata->user_id
                ];

                $this->transaction_model->create($trans_data);

                # Deposit
                $res = $this->transaction_model->deposit($request->amount, $request->metadata->user_id);
                if (is_array($res)) {
                    $feedback = $res;
                }
            }

            if ($request->status == 'failed') {
                # Mark deposit transaction status as failed (state_id=3)
                $data = ['state_id' => 3, 'status' => 'failed'];
                $this->transaction_model->update_deposit_request($request->id, $data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    } */

    public function get_password_reset_link()
    {
        $this->load->library('form_validation');
        $this->load->config('email');
        $this->load->library('email');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $feedback['error'] = true;
        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            $result = $this->auth_model->get_password_reset_link($post_data);
            if ($result['status']) {
                // send to email
                $to = $post_data['email'];
                $subject = "Password Reset Link - uccfstext";
                $reset_link = $result['reset_link'];
                $message = 'Here is the link to use for resetting your password. ' . '<br>';
                $message .= "<a href='{$reset_link}'> {$reset_link} </a>" . '<br><br>';
                $message .= "Note: It expires in 5 minutes. <br>";

                //$headers = "MIME-Version: 1.0" . "\r\n";
                //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                //$headers .= 'From: <enquiry@example.com>' . "\r\n";

                //mail($to, $subject, $message, $headers);


                $this->config->set_item('smtp_user', 'support@uccfstext.com');
                $this->config->set_item('smtp_pass', 'sms@uccfs');

                $from = $this->config->item('smtp_user');

                $this->email->set_newline("\r\n");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);

                if ($this->email->send()) {
                    $feedback = [
                        'status' => true,
                        'message' => 'Password reset link has been sent to your email'
                    ];
                    http_response_code(200);
                } else {
                    //show_error($this->email->print_debugger());
                    $feedback = [
                        'status' => false,
                        'message' => 'something went wrong. Failed to send password reset link. contact support@uccfstext.com'
                    ];
                    http_response_code(500);
                }
            } else {
                $feedback = $result;
                http_response_code(500);
            }
        }

        echo json_encode($feedback);
    }

    public function load_password_reset_form($token)
    {
        $this->load->helper('url');
        #Validate token
        $token_data = $this->auth_model->validate_password_reset_token($token);
        if (empty($token_data)) {
            header("Content-Type:text/html; charset=UTF-8");
            $this->load->view('forgot_password/invalid_token');
            //$data['data'] = $token_data;
            //$this->load->view('forgot_password/reset_form', $data['data']);
        } else {
            header("Content-Type:text/html; charset=UTF-8");
            $data['data'] = $token_data;
            $this->load->view('forgot_password/reset_form', $data['data']);
        }
    }

    public function reset_password()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_new_password', 'Password Confirmation', 'trim|required|matches[new_password]');

        $feedback['error'] = true;
        $feedback['status'] = false;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
            echo json_encode($feedback);
            die;
        } else {
            $data['id'] = $this->input->post('user_id');
            $data['password'] = $this->input->post('new_password');

            $feedback = $this->user_model->update($data);

        }

        if($feedback['status'] == true) {
            header("Content-Type:text/html; charset=UTF-8");
            echo "<p style='color: green; margin: 2em;'> Password has been reset successfully. You can now log in with your new password. </p>";
            die;

        } else {
            print_r($feedback);
            header("Content-Type:text/html; charset=UTF-8");
            echo "<p style='color: red; margin: 2em;'> Operation failed. try again or contact support via email (support@uccfstext.com) </p>";
            die;
        }
    }
}
