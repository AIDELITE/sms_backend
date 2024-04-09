<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['account-balance/refresh'] = 'transaction/refresh_acc_bal';
$route['signup'] = 'auth/signup';
$route['login'] = 'auth/login';
$route['password-reset/(:any)'] = 'auth/load_password_reset_form/$1';
$route['forgot-password'] = 'auth/get_password_reset_link';
$route['verify/token'] = 'auth/verify_jwt';
$route['api-key/new/(:num)'] = 'auth/apiKey/$1';
$route['phone-book/contacts'] = 'contact/index';
$route['phone-book/contact/create'] = 'contact/create';
$route['phone-book/user/contacts'] = 'contact/contacts';
$route['phone-book/contact/edit/(:num)'] = 'contact/edit/$1';
$route['phone-book/contact/delete/(:num)'] = 'contact/delete/$1';
$route['messages'] = 'message/index';
$route['messages/user'] = 'message/user';
$route['messages/(:num)'] = 'message/get_message/$1';
$route['transactions'] = 'transaction/index';
$route['transactions/user'] = 'transaction/user';
$route['transactions/sentepay_callback'] = 'transaction/sentepay_callback_url';
$route['users'] = 'user/index';
$route['user/update/(:num)'] = 'user/update/$1';
$route['contact_support'] = 'utils/contact_support';
