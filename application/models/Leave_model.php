<?php
    /**
     * CUTIeSys - Dev version 0.1
     * Author: Ismail Lebai Saleh | ismail@null.net | www.isha.my
     * 
     * Auth_model - A model to use for authentication and whatever that is related to it (eg: get details of user during authentication)
     */
    
    class Leave_model extends CI_Model 
    {
        protected $CI;
        
        public function __construct()
        {
            parent::__construct();
            
            $this->CI =& get_instance();
            $this->CI->load->helper('general_helper');
        }
        
        /**
         * newLeave - insert new leave application into db
         * 
         * @param array $data The user input
         * @return int insert_id
         */
        public function newLeave($data = [])
        {
            $sql = 'INSERT INTO `leave` (`apply_user_id`, `start_date`, `end_date`, `reason`) VALUES(?,?,?,?)';
            $this->db->query($sql, $data);
            
            return $this->db->insert_id();
        }
        
        /**
         * leaveListByUser - get the list of leave by the user (from current date)
         * 
         * @param int $user_id The user id
         * @return array the list of the leave
         */
        public function leaveListByUser($user_id = 0)
        {
            $sql = "SELECT `start_date`, `end_date`, `reason`, (case when (approve_user_id IS NULL) THEN 'Pending' ELSE 'Approve' END) as `status` FROM `leave` WHERE `apply_user_id` = ? AND (`end_date` > NOW() OR `approve_user_id` IS NULL)";
            $query = $this->db->query($sql, [(int)$user_id]);
            
            if ($query->num_rows() > 0)
            {
                return $query->result_array();
            }
            
            return [];
        }
        
        /**
         * leaveListHistoryByUser - get the list of leave by the user (from current date)
         * 
         * @param int $user_id The user id
         * @return array the list of the leave history
         */
        public function leaveListHistoryByUser($user_id = 0)
        {
            $sql = "SELECT `start_date`, `end_date`, `reason` FROM `leave` WHERE `apply_user_id` = ? AND `end_date` < NOW() AND `approve_user_id` IS NOT NULL";
            $query = $this->db->query($sql, [(int)$user_id]);
            
            if ($query->num_rows() > 0)
            {
                return $query->result_array();
            }
            
            return [];
        }
        
        /**
         * leaveListApproval - get the list of leave that is yet to be approve
         * 
         * @return array the list of the leave to be approve
         */
        public function leaveListApproval()
        {
            $sql = "SELECT l.id, u.full_name, l.start_date, l.end_date, l.reason, '' as action FROM `leave` l LEFT JOIN `user` u ON l.apply_user_id = u.id WHERE l.approve_user_id IS NULL";
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0)
            {
                return $query->result_array();
            }
            
            return [];
        }
        
        /**
         * leaveApprove - get the list of leave that is yet to be approve
         * 
         * @param $leave_id An array of the leave id to be approve
         * @return int the affected row count
         */
        public function leaveApprove($leave_id = 0, $approve_user_id = 0)
        {
            $sql = "UPDATE `leave` SET approve_user_id = ? WHERE id = ?";
            $this->db->query($sql, [$approve_user_id, $leave_id]);
            
            return $this->db->affected_rows();
        }
        
        /**
         * leaveMainList - get the list of leave to show on dashboard
         * 
         * @return array the list of the leave
         */
        public function leaveMainList($start_date, $end_date)
        {
            $sql = "SELECT u.full_name, l.start_date, l.end_date FROM `leave` l LEFT JOIN `user` u ON l.apply_user_id = u.id WHERE l.approve_user_id IS NOT NULL AND end_date > ? AND start_date < ?";
            $query = $this->db->query($sql, [$start_date, $end_date]);
            
            if ($query->num_rows() > 0)
            {
                return $query->result_array();
            }
            
            return [];
        }

    }
?>