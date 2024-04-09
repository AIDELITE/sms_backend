<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of SMS_lib
 *
 * @author Reagan Ajuna
 */

use AfricasTalking\SDK\AfricasTalking;
use Instasent\SMSCounter\SMSCounter;
use GuzzleHttp\Client;

class Sms_lib
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library("session");
        $this->CI->load->library("check_auth");
        // $this->load->library("beyonic_transactions");
        $this->CI->load->model('user_model');
        $this->CI->load->model('transaction_model');
        $this->CI->load->model('message_model');
        //$this->load->model('contact_model');
        $this->CI->load->model('sms_provider_model');

        $auth_user = (array) $this->CI->check_auth->get_user();
        $this->user_id = $auth_user['id'];

        $this->sms_provider = $this->CI->sms_provider_model->get(1);

        // Set your app credentials
        $username = $this->sms_provider['auth_username'];
        $apiKey = $this->sms_provider['auth_api_token'];

        // Initialize the SDK
        $AT = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $this->sms = $AT->sms();
    }

    public function send_sms($data)
    {
        // format numbers
        $numbers_array = explode(',', $data['recipients']);
        $numbers = [];
        foreach ($numbers_array as $key => $value) {

            $numbers[] = $this->format_contact($value);
        }

        try {

            $receipients = implode(",", $numbers);

            $send_data = array(
                'to' => $receipients,
                'message' => $data['message']
            );
            if (!empty($data['from'])) {
                $send_data['from'] = $data['from'];
            }
            // send
            $result = $this->sms->send($send_data);
            return true;
        } catch (Exception $e) {
            return false;
        }
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
