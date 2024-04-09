<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of Send_email
 *
 * @author Reagan Ajuna
 */

class Email_lib
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function send_email($data)
    {
        $this->CI->load->config('email');
        $this->CI->load->library('email');


        // send to email

        $to = $data['email'];
        $subject = $data['subject']; // ;
        //$reset_link = $result['reset_link'];
        $message = $data['message'];

        //$headers = "MIME-Version: 1.0" . "\r\n";
        //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        //$headers .= 'From: <enquiry@example.com>' . "\r\n";

        //mail($to, $subject, $message, $headers);


        $this->CI->config->set_item('smtp_user', 'support@uccfstext.com');
        $this->CI->config->set_item('smtp_pass', 'sms@uccfs');

        $from = $this->CI->config->item('smtp_user');

        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from($from);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);

        if ($this->CI->email->send()) {
            return true;
        }
        return false;
    }
}
