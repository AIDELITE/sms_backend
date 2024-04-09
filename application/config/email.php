

<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'mail.uccfstext.com', 
    'smtp_port' => 465,
    'smtp_user' => 'contact-form@uccfstext.com',
    'smtp_pass' => 'WeGoCranes1',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '15', //in seconds
    'charset' => 'utf-8',
    'wordwrap' => TRUE
);


