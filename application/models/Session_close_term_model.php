<?php
class Session_close_term_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function select_active_terminal_list(){
        $sql="select * from active_terminal where check_sum<>0";
        $result = $this->db->query($sql,array());
        return $result;
    }
    
    
        function logout_current_session($userid){           
            
			//Getting database id of the terminal//
            $sql="select person_id from person where user_id=?";
            $result = $this->db->query($sql,array($userid));
            if($result->num_rows()>0){
                $person_id=$result->row()->person_id;
            }else{
				$person_id='';
			}
            
            //set zero active user sum_value
            
            $sql="update active_terminal set check_sum=0,last_loggedout=now() where person_id=?";
    		$result = $this->db->query($sql,array($person_id));
    		return $result;
    }//end of function





}//final

?>