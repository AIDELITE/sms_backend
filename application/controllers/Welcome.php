<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	
	public function index()
	{
		echo json_encode([
			'health-check' => 'API live and runnig just fine!'
		]);
	}
}
