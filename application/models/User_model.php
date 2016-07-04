<?php
    /**
     * CUTIeSys - Dev version 0.1
     * Author: Ismail Lebai Saleh | ismail@null.net | www.isha.my
     * 
     * User_model - A model to use for user related database action
     */
    
    class User_model extends CI_Model 
    {
        protected $CI;
        
        public function __construct()
        {
            parent::__construct();
            
            $this->CI =& get_instance();
            $this->CI->load->helper('general_helper');
        }
        
        /**
         * newUser - insert new user into db
         * 
         * @param array $data The user details
         * @return int insert_id
         */
        public function newUser($data = [])
        {
            $sql = 'INSERT INTO `user` (`login`, `password`, `full_name`, `is_approver`) VALUES(?,?,?,?)';
            $this->db->query($sql, $data);
            
            return $this->db->insert_id();
        }
        
        /**
         * newUser - insert new user into db
         * 
         * @return array the list of user(s)
         */
        public function listUser()
        {
            $sql = "SELECT full_name, login, (case when (is_approver IS NULL) THEN 'No' ELSE 'Yes' END) as `is_approver`, id FROM user";
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0)
            {
                return $query->result_array();
            }
            
            return [];
        }
        
        /**
         * resetUserPassword - change the user password
         * 
         * @param $user_id the user id
         * @param $password the hash user password
         * @return int the affected row count
         */
        public function resetUserPassword($user_id, $password)
        {
            $sql = "UPDATE user SET password = ? WHERE id = ?";
            $this->db->query($sql, [$password, $user_id]);
            
            return $this->db->affected_rows();
        }
        
        /**
         * leaveListByUser - get the list of leave by the user (from current date)
         * 
         * @param int $user_id The user id
         * @return array the list of the leave
         */
//         public function leaveListByUser($user_id = 0)
//         {
//             $sql = "SELECT `start_date`, `end_date`, `reason`, (case when (approve_user_id IS NULL) THEN 'Pending' ELSE 'Approve' END) as `status` FROM `leave` WHERE `apply_user_id` = ? AND (`end_date` > NOW() OR `approve_user_id` IS NULL)";
//             $query = $this->db->query($sql, [(int)$user_id]);
            
//             if ($query->num_rows() > 0)
//             {
//                 return $query->result_array();
//             }
            
//             return [];
//         }
        
        /**
         * leaveListHistoryByUser - get the list of leave by the user (from current date)
         * 
         * @param int $user_id The user id
         * @return array the list of the leave history
         */
//         public function leaveListHistoryByUser($user_id = 0)
//         {
//             $sql = "SELECT `start_date`, `end_date`, `reason` FROM `leave` WHERE `apply_user_id` = ? AND `end_date` < NOW() AND `approve_user_id` IS NOT NULL";
//             $query = $this->db->query($sql, [(int)$user_id]);
            
//             if ($query->num_rows() > 0)
//             {
//                 return $query->result_array();
//             }
            
//             return [];
//         }
        
        /**
         * leaveListApproval - get the list of leave that is yet to be approve
         * 
         * @return array the list of the leave to be approve
         */
//         public function leaveListApproval()
//         {
//             $sql = "SELECT l.id, u.full_name, l.start_date, l.end_date, l.reason, '' as action FROM `leave` l LEFT JOIN `user` u ON l.apply_user_id = u.id WHERE l.approve_user_id IS NULL";
//             $query = $this->db->query($sql);
            
//             if ($query->num_rows() > 0)
//             {
//                 return $query->result_array();
//             }
            
//             return [];
//         }
        
        /**
         * leaveApprove - get the list of leave that is yet to be approve
         * 
         * @param $leave_id An array of the leave id to be approve
         * @return int the affected row count
         */
//         public function leaveApprove($leave_id = 0, $approve_user_id = 0)
//         {
//             $sql = "UPDATE `leave` SET approve_user_id = ? WHERE id = ?";
//             $this->db->query($sql, [$approve_user_id, $leave_id]);
            
//             return $this->db->affected_rows();
//         }

    }
?>