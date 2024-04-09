<?php

/**
 * @Author Reagan Ajuna
 */
if (!defined('BASEPATH')) {
    exit("No direct script access allowed");
}

use Firebase\JWT\JWT;

class Jwt_lib
{

    public function __construct()
    {
        $this->secret = 'sms_platform_jwt_key'; // should preferably be stored in env variables
    }

    public function get_jwt_token($payload)
    {
        /* 
     $payload = array(
        "iss" => "gmt_sms_platform",
        "aud" => "http://example.com",
        "iat" => 1356999524,
        "nbf" => 1357000000
     );
     */
        return JWT::encode($payload, $this->secret);
    }

    public function decode_jwt_token($token)
    {
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $this->secret, array('HS256'));

            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
