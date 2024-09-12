<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

class Utils extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->model('auth_model');
        $this->load->model('user_model');
        $this->load->model('transaction_model');
        $this->load->model('message_model');
        $this->load->library('check_auth');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = (array) $this->check_auth->get_user();

        // set all responses as json
        header("Content-Type:application/json");
    }


    public function contact_support()
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

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'message', 'required');

        $feedback['error'] = true;
        $feedback['status'] = false;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            $to = 'rajuna@uccfs.co.ug';
            $from = $this->config->item('smtp_user');
            $this->email->set_newline("\r\n");
            $this->email->from($from);
            $this->email->to($to);
            $this->email->subject($post_data['subject']);
            $this->email->message($post_data['message']);
            $this->email->reply_to($post_data['email'], $post_data['name']);

            if ($this->email->send()) {
                $feedback = [
                    'status' => true,
                    'message' => 'Thanks for contacting us. We shall get back to you as soon as possible'
                ];
                http_response_code(200);
            } else {
                //show_error($this->email->print_debugger());
                $feedback = [
                    'status' => false,
                    'message' => 'something went wrong. Please try again'
                ];
                http_response_code(500);
            }
        }

        echo json_encode($feedback);
    }

    public function creditBalance()
    {
        
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }
        $user_id = $this->user['id'];
        $user = $this->get_user(intval($user_id));

        $feedback['data'] = $this->transaction_model->accountBalance($user_id);
        $feedback['success'] = true;
        $feedback['cost'] = $user['sms_rate'];
        echo json_encode($feedback);
    }

    public function totalFloatBalance()
    {
        
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }
        $user_id = $this->user['id'];
        $user = $this->get_user(intval($user_id));

        $feedback['data'] = $this->transaction_model->totalFloatBalance();
        $feedback['success'] = true;
        $feedback['cost'] = $user['sms_rate'];
        echo json_encode($feedback);
    }

    public function totalDepleted()
    {
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $feedback['data'] = $this->user_model->getTotalDepleted();
        $feedback['success'] = true;
        echo json_encode($feedback);
    }

    public function totalsentmessages()
    {
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $feedback['data'] = $this->message_model->get_total_sent();
        $feedback['success'] = true;
        echo json_encode($feedback);
    }

     public function creditBalance2()
    {
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }
        $user_id = $this->input->post('id');
        $user = $this->get_user(intval($user_id));

        $feedback['data'] = $this->transaction_model->accountBalance($user_id);
        $feedback['success'] = true;
        $feedback['cost'] = $user['sms_rate'];
        echo json_encode($feedback);
    }

    private function get_user($id)
    {
        $user = $this->auth_model->get_user($id);
        return $user;
    }

    public function get_total_connected_saccos(){
        if ($this->isAuthenticated == false) {
            $this->output->set_status_header(401);
            $feedback = [
                'success' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $feedback['data'] = $this->user_model->getTotalConnectedSaccos();
        $feedback['success'] = true;
        echo json_encode($feedback);
    }
}
