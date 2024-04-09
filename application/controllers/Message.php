<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

class Message extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->library("check_auth");
        //$this->load->library("beyonic_transactions");
        $this->load->model('message_model');
        // $this->load->library('jwt_lib');

        // check if authenticated
        $this->isAuthenticated = $this->check_auth->is_authenticated(); // api_key or jwt

        $this->is_valid_api_key = $this->check_auth->is_valid_api_key();
        $this->is_valid_jwt = $this->check_auth->is_valid_jwt();
        $this->user = (array) $this->check_auth->get_user();

        // set all responses as json
        header("Content-Type:application/json");
    }

    public function index()
    {
        if ($this->isAuthenticated==false) {
            $this->output->set_status_header(401);
            $feedback = [
                'status' => false,
                'error' => true,
                'message' => 'Access Denied, UnAuthorized'
            ];
            return $this->output->set_output(json_encode(($feedback)));
        }

        $data['messages'] = $this->message_model->get_messages();
        echo json_encode($data);
    }

    # Get User Messsages
    public function user()
    {
        $data['data'] = $this->message_model->get_messages("m.user_id = '{$this->user['id']}'");
        echo json_encode($data);
    }
    # Get single Messsage
    public function get_message($message_id)
    {
        $data = $this->message_model->get_message("m.user_id = '{$this->user['id']}' AND m.id = '{$message_id}' ");
        if(empty($data)) {
            $data = [];
        }
        echo json_encode($data);
    }

    ## Update Delivery Repoerts
    public function deliveryStatus(){
      $transactionNo    = $_POST["id"];
      //$phoneNumber  = $_POST["phoneNumber"];
      $status  = $_POST["status"];
      return $this->message_model->updateSmsStatus($status,$transactionNo);
    }
}
