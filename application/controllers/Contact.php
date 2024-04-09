<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

class Contact extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->library("check_auth");
        //$this->load->library("beyonic_transactions");
        $this->load->model('contact_model');
        $this->load->model('user_model');
        // $this->load->library('jwt_lib');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = (array) $this->check_auth->get_user();

        // set all responses as json
        header("Content-Type:application/json");
        if ($this->isAuthenticated==false) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }
    }

    # Get All contacts (Admin)
    public function index()
    {
        if ($this->user['role'] != 1) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $data['contacts'] = $this->contact_model->get_contacts();
        echo json_encode($data);
    }

    public function create()
    {
        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);
        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        # id	user_id	name	phone_numbers	status_id	date_created	date_modified

        $this->form_validation->set_rules('name', 'Contact Name', 'required');
        $this->form_validation->set_rules('phone_numbers', 'Phone Number(s)', 'required');

        $feedback['error'] = true;

        // echo json_encode($post_data);die;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            $this->db->trans_start();
            $contact = [
                'date_created' => date('Y-m-d'),
                'name' => $post_data['name'],
                'user_id' => $this->user['id'],
                'phone_numbers' => $post_data['phone_numbers'],
                'status_id' => 1
            ];

            $res = $this->contact_model->create($contact);
            if (is_numeric(($res))) {
                $feedback = [
                    'message' => 'Contact saved',
                    'status' => true
                ];
                http_response_code(200);
            } else {
                $feedback = $res;
                http_response_code(500);
            }

            $this->db->trans_complete();
        }

        echo json_encode($feedback);
    }


    # Get user contacts 
    public function contacts()
    {
        $data['contacts'] = $this->contact_model->get_contacts("user_id = '{$this->user['id']}'");
        echo json_encode($data);
    }

    public function edit($contact_id)
    {
        $this->load->library('form_validation');

        $post_data = json_decode(file_get_contents('php://input'), true);

        if (!empty($post_data)) {
            $this->form_validation->set_data($post_data);
        } else {
            $post_data = $this->input->post();
        }

        if (!isset($post_data['name'])) {
            $this->form_validation->set_rules('phone_numbers', 'Phone Number(s)', 'required');
        }
        if (!isset($post_data['phone_numbers'])) {
            $this->form_validation->set_rules('name', 'Contact name', 'required');
        }

        if (isset($post_data['name']) && isset($post_data['phone_numbers'])) {
            $this->form_validation->set_rules('phone_numbers', 'Phone Number(s)', 'required');
            $this->form_validation->set_rules('name', 'Contact name', 'required');
        }

        $feedback['error'] = true;

        // echo json_encode($post_data);die;

        if ($this->form_validation->run() === FALSE) {
            $feedback['message'] = $this->form_validation->error_array();
            http_response_code(400);
        } else {
            $this->db->trans_begin();

            $userId = $this->user['id'];

            $contact = $this->contact_model->get_contact("user_id='{$userId}' AND id='{$contact_id}' ");

            if (!$contact) {

                $feedback = [
                    'message' => 'Contact does not exist',
                    'status' => false,
                    'error' => true
                ];
                http_response_code((400));
                echo json_encode($feedback);
                return;
            }

            if ($this->contact_model->update($contact_id, $post_data)) {
                $feedback = [
                    'message' => 'Contact Updated',
                    'status' => true,
                ];
                http_response_code((200));
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
        }
        echo json_encode($feedback);
    }


    public function delete($id)
    {
        $this->db->trans_begin();

        $userId = $this->user['id'];

        $contact = $this->contact_model->get_contact("user_id='{$userId}' AND id='{$id}' ");

        if (!$contact) {

            $feedback = [
                'message' => 'Contact does not exist',
                'status' => false,
                'error' => true
            ];
            http_response_code(400);

            echo json_encode($feedback);
            return;
        }

        if ($this->contact_model->delete($id)) {
            $feedback = [
                'message' => 'Contact Deleted',
                'status' => true,
            ];
            http_response_code(200);
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
}
