<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * CUTIeSys - Dev version 0.1
     * Author: Ismail Lebai Saleh | ismail@null.net | www.isha.my
     * 
     * Login - A login controller to handle all the login/logout action/request
     */

class Login extends CI_Controller {
	
	public function __construct()
    {
		parent::__construct();
		
		$this->load->model('Auth_model');
	}
	
	public function index()
	{
		if ($this->input->post('login') && $this->input->post('password'))
		{
			$user_id = $this->Auth_model->doAuth($this->input->post('login'), $this->input->post('password'));
			if ($user_id > 0)
			{
				$user_data = $this->Auth_model->getUserDetails($user_id);
				
				if (empty($user_data))
				{
					//TODO add error message - when user have no details (perhaps deleted?)
					redirect('main');
				}
				
				$_SESSION['cutiesys_login'] = 'yes';
				$_SESSION['cutiesys_user_data'] = $user_data;
				redirect('main/member');
			}
			else
			{
				//TODO add error message - when user do not exist
				redirect('main');
			}
		}
		else
		{
			//TODO add error message - when did not receive any data/or empty
			redirect('main');
		}
		
		
	}
	
	public function logout()
	{
		unset($_SESSION['cutiesys_login']);
		unset($_SESSION['cutiesys_user_data']);
		redirect('main');
	}
}
