<?php
/**
 * @Author Eric
 */
if (!defined('BASEPATH'))
    exit("No direct script access allowed");
class Beyonic_transactions {
        protected $mm_channel_data;
        //protected $CI;
        public function __construct(){
          $this->CI = &get_instance();
          $this->CI->load->model('payment_provider_model');
          $this->provider= $this->CI->payment_provider_model->get_one('id = 1');          
          //Setting the api key
          Beyonic::setApiKey($this->provider['auth_api_key']);//"d1432c14c60be76683ad8f869d4c0d3a1f0af028"
          //version of the api
          define("BEYONIC_CLIENT_VERSION", "0.0.9");
        }

        //responsible for formatting contacts into international format
        public function format_contact($contact){

            if (preg_match("/^[\+]+[0-9]{12,12}$/", $contact)) {
                $mobile_number=$contact;
            }elseif (preg_match("/^[07]+[0-9]{9,10}$/", $contact)){
                $mobile_number='+256'.substr($contact,-9);
            }else {
              // Incorrect mobile number
              $feedback = [
                'status' => false,
                'message' => 'Invalid Phone number',
                'error' => true
              ];
              http_response_code(400);
              echo json_encode($feedback);
              die;
            }
            return $mobile_number;
        }

        //responsible for creating a collection request
        public function new_collection($col_instructions){
          $phonenumber=$this->format_contact($col_instructions['phone_number']);
          try {
            $collection_request = Beyonic_Collection_Request::create(array(
                "phonenumber" =>$phonenumber,// $phonenumber
                "amount" => $col_instructions['amount'],
                "currency" => $this->provider['currency'],// UGX
                "success_message" => "You have Deposited {amount} /=.Thank you for depositing",
                "metadata" => array("time_stamp"=>strtotime('now'), 'user_id' => $col_instructions['user_id']),
                "send_instructions" => True
              ));
            return $collection_request;
          } catch (Beyonic_Exception $e) {
           return $e->getMessage().' '.$e->responseBody;
          }
        }

        //Retreiving single collection requests from the system
        public function get_one_collection_request($id){
          try {
            $collection_request = Beyonic_Collection_Request::get($id);
            return $collection_request;
          } catch (Beyonic_Exception $e) {
            return $e->getMessage().' '.$e->responseBody;
          }
        }

        //Retreiving all collection requests from the system
        public function get_collection_request($filter=[]){
          try {
            $collection_requests = Beyonic_Collection_Request::getAll($filter);
            return $collection_requests;
          } catch (Beyonic_Exception $e) {
            return $e->getMessage().' '.$e->responseBody;
          }
        }

        //This function can be used to send mobile money or airtime to any number
        public function new_payment($pay_instructions){
          $phonenumber=$this->format_contact($pay_instructions['phone_number']);
          try {
              $payment = Beyonic_Payment::create(array(
                "phonenumber" => $phonenumber,
                "first_name" => $pay_instructions['first_name'],
                "last_name" => $pay_instructions['last_name'],
                "amount" => $pay_instructions['amount'],
                "currency" => "UGX",
                "account" => "1",//From beyonic dashboard. The id of the account money will be deduct
                "description" => $pay_instructions['description'],//should be limited to 140 characters
                "payment_type" => $pay_instructions['payment_type'],//money for mobile money or airtime if you want to send airtime
                "metadata" => array("mnt_trx_id"=>$pay_instructions['merchant_transaction_id']),
                "callback_url" => "https://my.website/payments/callback"
              ));
              return $payment;
            } catch (Beyonic_Exception $e) {
             return $e->getMessage().' '.$e->responseBody;
            }
        }

        //This function can be used to send mobile money or airtime to many clinets or numbers
        public function bulk_payment($client_payment,$pay_instructions){
           try {
                $payment = Beyonic_Payment::create(array(
                  "currency" => "UGX",
                  "account" => "1",//From beyonic dashboard. The id of the account money will be deduct
                  "payment_type" => $pay_instructions['payment_type'],//money for mobile money or airtime if you want to send airtime
                  "metadata" => array("merchant_transaction_id"=>$pay_instructions['merchant_transaction_id']),
                  "recipient_data" => array($client_payment),
                  "callback_url" => "https://my.website/payments/callback"
                ));
                return $payment;
            } catch (Beyonic_Exception $e) {
             return $e->getMessage().' '.$e->responseBody;
            }
        }

        //Retreiving all payments from the system
        public function get_payment_list($filter=[]){
          try{
              $payments = Beyonic_Payment::getAll($filter);
              return $payments;
            } catch (Beyonic_Exception $e) {
              return $e->getMessage().' '.$e->responseBody;
            }
        }

        //claiming un matched deposit
        public function claim_collection($claim_data){
            $phonenumber=$this->format_contact($claim_data['phone_number']);
            try {
              $collections = Beyonic_Collection::getAll(array(
                "phonenumber" => $phonenumber,
                "remote_transaction_id" => $claim_data['remote_transaction_id'],
                "claim" => "True",
                "amount" =>  $claim_data['amount'],
              ));
              return $collections;
            } catch (Beyonic_Exception $e) {
               return $e->getMessage().' '.$e->responseBody;
            }
        }

        #TODO : 
        ## CALLBACK endpoint for Updating user's acc balance on a successful Deposit

}