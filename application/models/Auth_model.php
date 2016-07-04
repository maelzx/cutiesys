<?php
    /**
     * CUTIeSys - Dev version 0.1
     * Author: Ismail Lebai Saleh | ismail@null.net | www.isha.my
     * 
     * Auth_model - A model to use for authentication and whatever that is related to it (eg: get details of user during authentication)
     */
    
    class Auth_model extends CI_Model 
    {
        protected $CI;
        
        public function __construct()
        {
            parent::__construct();
            
            $this->CI =& get_instance();
            $this->CI->load->helper('general_helper');
        }
        
        /**
         * do_auth - do login the user
         * 
         * @param string $username Input the login username
         * $param string $password Input the login password
         * @return int $user_id - $user_id > 0 if user authentication success, $user_id = 0 if user authentication failed
         */
        public function doAuth($username, $password)
        {
            
            //check the useraname & password is not empty
            if (empty($username) || empty($password))
            {
                return 0;
            }
            
            $sql = "SELECT id, password FROM user WHERE login = ?";
            $query = $this->db->query($sql, [$username]);
            
            if ($query->num_rows() > 0)
            {
                $result = $query->row_array();
                
                if (genHelDoPasswordVerify($password, $result['password']))
                {
                    return $result['id'];
                }
                
                return 0;
            }
            
            return 0;
        }
        
        /**
         * get_user_details - Get all the user details
         *
         * @param int $userid Input the user id
         * @return array $return_arr - array with all user details if success, empty array if failed
         */
        public function getUserDetails($userid)
        {
            //check the userid not empty
            if (empty($userid))
            {
                return [];
            }
            
            $sql = "SELECT id, login, full_name, is_approver FROM user WHERE id = ?";
            $query = $this->db->query($sql, [$userid]);
            
            if ($query->num_rows() > 0)
            {
                return $query->row_array();
            }
            
            return [];
        }

    }
?>