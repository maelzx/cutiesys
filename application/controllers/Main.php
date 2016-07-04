<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	
	protected $data;
	
	public function __construct()
    {
		parent::__construct();
		
		$this->load->model('Leave_model');
		$this->load->model('User_model');
		$this->load->helper('general_helper');
	}
	
	public function index()
	{
		$this->load->view('main/main');
	}
	
	public function member()
	{
		//check if logged in
		$login_status = $this->session->cutiesys_login;
		if (empty($login_status) || $login_status != 'yes')
		{
			//TODO - error message when session is invalid
			redirect('main');
		}
		
		$this->data['page_title'] = 'CUTIeSys Members Section';
		
		$this->data['page_member_name'] = $this->session->cutiesys_user_data['full_name'];
		
		$this->load->view('main/member', $this->data);
		
	}
	
	public function member_ajax($action = '')
	{
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
		
		$return_arr = [];
		//check if logged in
		$login_status = $this->session->cutiesys_login;
		if (empty($login_status) || $login_status != 'yes')
		{
			//TODO - error message when session is invalid
			$return_arr = ['status' => 'fail', 'error_message' => 'Please login to access this section'];
		}
		
		$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_LEAV_001]'];
		
		$user_id = $this->session->cutiesys_user_data['id'];
		
		if ($this->input->post('apply_leave') && $this->input->post('user_id'))
		{
			//do the apply leave process
			//check if all input is there
			if ($this->input->post('start_date_end_date') && $this->input->post('reason'))
			{
				$dates 		= explode('-', $this->input->post('start_date_end_date'));
				$start_date = genHelDoConvertDate(rtrim($dates[0]), 'd/m/Y', 'Y-m-d');
				$end_date 	= genHelDoConvertDate(ltrim($dates[1]), 'd/m/Y', 'Y-m-d');
				$reason 	= $this->input->post('reason');
				$user_id 	= $this->input->post('user_id');
				
				$data = [$user_id, $start_date, $end_date, $reason];
				
				//insert into database
				$insert_id = $this->Leave_model->newLeave($data);
				
				//simple checking, if int then assume insert is working ok
				if ($insert_id > 0)
				{
					$return_arr = ['status' => 'success', 'success_message' => 'Leave successfully applied.'];
				}
				else
				{
					$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_LEAV_002]'];
				}
			}
		}
		
		if ($this->input->post('approve_leave') && $this->input->post('leave_id'))
		{
			if ($this->session->cutiesys_user_data['is_approver'] != 1)
			{
				$return_arr = ['status' => 'fail', 'error_message' => 'You\'re not allowed to perform this action [ERR_LEAV_004]'];
			}
			else
			{
				$leave_id = [];
				$count_row = 0;
				
				foreach ($this->input->post('leave_id') as $id)
				{
					//$leave_id[] = $id;
					$affected_row = $this->Leave_model->leaveApprove($id, $user_id);
					if ($affected_row > 0)
					{
						$count_row++;
					}
				}

				if ($count_row > 0)
				{
					$return_arr = ['status' => 'success', 'success_message' => 'Successfully approve '. $count_row .' leave(s)'];
				}
				else
				{
					$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_LEAV_005]'];
				}
			}
			
		}
		
		if ($action == 'leave_list')
		{
			//$user_id = $this->session->cutiesys_user_data['id'];
			
			$processed_leave_arr = ['data' => []];
			
			foreach ($this->Leave_model->leaveListByUser($user_id) as $leave)
			{
				$processed_leave_arr['data'][] = $leave;
			}
			
			$return_arr = $processed_leave_arr;
		}
		
		if ($action == 'leave_history')
		{
			//$user_id = $this->session->cutiesys_user_data['id'];
			
			$processed_leave_arr = ['data' => []];
			
			foreach ($this->Leave_model->leaveListHistoryByUser($user_id) as $leave)
			{
				$processed_leave_arr['data'][] = $leave;
			}
			
			$return_arr = $processed_leave_arr;
		}
		
		if ($action == 'leave_approval')
		{
			if ($this->session->cutiesys_user_data['is_approver'] != 1)
			{
				$return_arr = ['status' => 'fail', 'error_message' => 'You\'re not allowed to perform this action [ERR_LEAV_003]'];
			}
			else
			{
				$processed_leave_arr = ['data' => []];
			
				foreach ($this->Leave_model->leaveListApproval() as $leave)
				{
					$processed_leave_arr['data'][] = $leave;
				}

				$return_arr = $processed_leave_arr;
			}
			
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return_arr));
		
	}
	
	public function user()
	{
		//check if logged in
		$login_status = $this->session->cutiesys_login;
		if (empty($login_status) || $login_status != 'yes')
		{
			//TODO - error message when session is invalid
			redirect('main');
		}
		
		$this->data['page_title'] = 'CUTIeSys Manager User';
		
		$this->data['page_member_name'] = $this->session->cutiesys_user_data['full_name'];
		
		$this->load->view('main/user', $this->data);
	}
	
	public function user_ajax($action = '')
	{
		if ($this->session->cutiesys_user_data['is_approver'] != 1)
		{
			$return_arr = ['status' => 'fail', 'error_message' => 'You\'re not allowed to perform this action [ERR_LEAV_006]'];
		}
		else
		{
			
			if ($this->input->post('new_user'))
			{
				$full_name 		= $this->input->post('full_name');
				$login 			= $this->input->post('login');
				$password 		= $this->input->post('password');
				$is_approver 	= $this->input->post('is_approver');
				
				$password 		= password_hash($password, PASSWORD_DEFAULT);
				
				$data = [$login, $password, $full_name, $is_approver];
				
				//insert into database
				$insert_id = $this->User_model->newUser($data);
				
				//simple checking, if int then assume insert is working ok
				if ($insert_id > 0)
				{
					$return_arr = ['status' => 'success', 'success_message' => 'User successfully created.'];
				}
				else
				{
					$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_USR_001]'];
				}
			}
			
			if ($this->input->post('reset_password'))
			{
				$userid 		= $this->input->post('userid');
				$password 		= $this->input->post('password');
				
				$password 		= password_hash($password, PASSWORD_DEFAULT);
				
				//insert into database
				$affected_row = $this->User_model->resetUserPassword($userid, $password);
				
				//simple checking, if int then assume insert is working ok
				if ($affected_row > 0)
				{
					$return_arr = ['status' => 'success', 'success_message' => 'User password successfully reset.'];
				}
				else
				{
					$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_USR_002]'];
				}
			}
			
			
			if ($action == 'user_list')
			{
				$processed_leave_arr = ['data' => []];
			
				foreach ($this->User_model->listUser() as $user)
				{
					$processed_leave_arr['data'][] = $user;
				}

				$return_arr = $processed_leave_arr;
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return_arr));
	}
	
	public function leave_ajax()
	{
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
		
		$return_arr = [];
		//check if logged in
		$login_status = $this->session->cutiesys_login;
		if (empty($login_status) || $login_status != 'yes')
		{
			//TODO - error message when session is invalid
			$return_arr = ['status' => 'fail', 'error_message' => 'Please login to access this section'];
		}
		
		//$return_arr = ['status' => 'fail', 'error_message' => 'Something went wrong, please try again [ERR_LEAV_002]'];
		
		parse_str($_SERVER['QUERY_STRING'], $parse_query_str);
		
		$raw_data_arr = $this->Leave_model->leaveMainList($parse_query_str['start'], $parse_query_str['end']);
		
		$return_arr = [];
		
		foreach ($raw_data_arr as $leave_data)
		{
			$return_arr[] = ['title' => $leave_data['full_name'] . ' On Leave', 'start' => $leave_data['start_date'], 'end' => genHelDoAddOneDayToDate($leave_data['end_date']), 'allDay' => 'true'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return_arr));
	}
}
