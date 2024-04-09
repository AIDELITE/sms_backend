<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->library("check_auth");
        $this->load->model('auth_model');

        //$this->load->library("beyonic_transactions");
        $this->load->model('user_model');
        // $this->load->library('jwt_lib');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = $this->check_auth->get_user();

        // set all responses as json
        header("Content-Type:application/json");
    }

    public function index()
    {
        if (!$this->isAuthenticated && $this->user['role'] != 1) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $data['data'] = $this->user_model->get_users();
        echo json_encode($data);
    }

      public function user()
    {
        if (!$this->isAuthenticated && $this->user['role'] != 1) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'success'=>false,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }else{
        $feedback['success']=true;
        $feedback['data'] = $this->auth_model->get_user($this->input->post('user_id'));
        echo json_encode($feedback);
       }
    }

      public function UserType()
    {
        if (!$this->isAuthenticated && $this->user['role'] != 1) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'success'=>false,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }else{
        $feedback['success']=true;
        $feedback['data'] = $this->user_model->get_user_types($this->input->post('user_id'));
        echo json_encode($feedback);
       }
    }

    public function change_password()
    {

        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data['password'])) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        }
        if (isset($post_data['confirm_password'])) {
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        }

        $feedback['error'] = true;
        $feedback['success'] = false;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = validation_errors();
            http_response_code(200);
        } else {
            if ($this->user_model->change_password($post_data)) {
                unset($feedback['error']);
                $feedback['status'] = true;
                $feedback['success'] = true;
                $feedback['message'] = 'Password Changed Successfully';
                http_response_code(200);
            } else {
                //http_response_code(400);
                $feedback = $result;
            }
        }

        echo json_encode($feedback);
    }

    public function update($id=false)
    {

        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data['password'])) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        }
        if (isset($post_data['confirm_password'])) {
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        }
        if ($this->input->post("user_type_id") == 1) {
            if (isset($post_data['firstname'])) {
                $this->form_validation->set_rules('firstname', 'Firstname', 'required');
            }
            
            if (isset($post_data['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Lastname', 'required');
            }
        }else{
           if (isset($post_data['organisation'])) {
                $this->form_validation->set_rules('organisation', 'Organisation', 'required');
            }
        }
        if (isset($post_data['mobile_number'])) {
            $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'required');
        }
         if (isset($post_data['sms_rate'])) {
            $this->form_validation->set_rules('sms_rate', ' SMS Charge', 'required');
        }


        $feedback['error'] = true;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(200);
        } else {
            if(is_numeric($id)){
            $post_data['id'] = $id;
            }
            $result = $this->user_model->update($post_data);

            if (($result['status'])) {
                unset($feedback['error']);
                $feedback['status'] = true;
                $feedback['success'] = true;
                $feedback['message'] = 'Your Information has been updated successfully';
                http_response_code(200);
            } else {
                //http_response_code(400);
                $feedback = $result;
            }
        }

        echo json_encode($feedback);
    }
}
