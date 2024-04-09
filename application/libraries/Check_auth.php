<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */
class Check_auth
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('auth_model');
        $this->CI->load->library('jwt_lib');

        // validate jwt token
        $this->isAuthenticated = false;
        $this->valid_jwt = false;
        $this->valid_api_key = false;
        $this->user = null;

        $token = $this->CI->input->get_request_header('Authorization');
        if (!empty($token)) {
            $token_parts = explode(" ", $token); // removing bearer string if it exists
            $token = $token_parts[count($token_parts) - 1];
            $decoded_token = $this->CI->jwt_lib->decode_jwt_token($token);

            if (!empty($decoded_token)) {
                $this->isAuthenticated = true;
                $this->valid_jwt = true;
                $this->user = $decoded_token['user'];
            }
        }

        $api_key = false;
        $api_key = $this->CI->input->get_request_header('api-key');
        if (!$api_key) {
            $post_data = json_decode(file_get_contents('php://input'), true);
            if (!empty($post_data['api_key'])) {
                $api_key = $post_data['api_key'];
            }

            if (empty($post_data['api_key']) && !empty($this->CI->input->post('api_key'))) {
                $api_key = $this->CI->input->post('api_key');
            }
        }
        // validate API key
        $user = $this->CI->auth_model->validate_api_key($api_key);
        if($user!=false) {
           $this->isAuthenticated = true;
           $this->user = $user;
           $this->valid_api_key = true;
        }
    }

    public function is_authenticated()
    {
        return $this->isAuthenticated;
    }

    public function is_valid_jwt()
    {
        return $this->valid_jwt;
    }

    public function is_valid_api_key()
    {
        return $this->valid_api_key;
    }

    public function get_user()
    {
        return $this->user;
    }
}
