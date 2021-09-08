<?php
class Person extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }
    function select_person(){
        $sql = "select person_id as cust_id,person_name, mobile_no,sex from person";
        $result = $this->db->query($sql);
            return $result;
    }

    


    

    function get_person_by_authentication($login_data){
        $sql="select
        person.person_id, person.person_name, person.person_cat_id,person.user_id,1 as is_logged_in
        from person
        inner join person_category on person.person_cat_id = person_category.person_cat_id
        where person.user_id=? and person.user_password=?";
        $result = $this->db->query($sql,array($login_data->userId,$login_data->userPassword));
        if($result->num_rows()>0){
            return $result->row();
        }else{
            $row['person_id']='0';
            $row['person_name']='not found';
            $row['person_cat_id']='0';
            $row['is_logged_in']=0;
            return (object)$row;
        }
    }
    
    
    function check_login($login_data){
       
       
            //$this->db->query("START TRANSACTION");
           
            
			//Getting database id of the terminal//
            $sql="select person_id from person where user_id=?";
            $result = $this->db->query($sql,array($login_data->userId));
            if($result->num_rows()>0){
                $person_id=$result->row()->person_id;
            }else{
				$person_id='';
			}
            
            
            
			//checking the current value of check_sum
			// $sql="select ifnull(max(check_sum),0) as check_sum_value,user_id from active_terminal
			//  where person_id=?";
			//  $result = $this->db->query($sql,array($person_id));
			//  $check_sum_value=$result->row()->check_sum_value;
			//  if($check_sum_value>0){
			//  	$row['person_id']='0';
			//  	$row['user_id']=$result->row()->user_id;
            // 	$row['person_name']='not found';
            // 	$row['person_cat_id']='0';
            // 	$row['is_logged_in']=0;
            // 	$row['is_currently_loggedin']=1;
            // 	return (object)$row;
			//  }else{
			 	 //check terminal by userid and psw//
	            $sql="select
		        person.person_id, person.person_name, person.person_cat_id,person.user_id,1 as is_logged_in
		        ,0 as is_currently_loggedin from person
		        inner join person_category on person.person_cat_id = person_category.person_cat_id
		        where person.user_id=? and person.user_password=?";
		        $result = $this->db->query($sql,array($login_data->userId,$login_data->userPassword));
			 	if($result->num_rows()>0){
			 		$person_cat_id=$result->row()->person_cat_id;
			 		if($person_cat_id==3){
						$sql="insert into active_terminal (
						  person_id
						  ,user_id
						  ,check_sum,last_loggedin) VALUES (?,?,?,now())
						  on duplicate key UPDATE id=last_insert_id(id),check_sum=check_sum+1,last_loggedin=now()";
            		$result2 = $this->db->query($sql,array($person_id,$login_data->userId,1));
            		
					}
					return $result->row();
			 		//insert or update the active_terminal
			 		
		        }else{
		            $row['person_id']='0';
		            $row['user_id']=0;
		            $row['person_name']='not found';
		            $row['person_cat_id']='0';
		            $row['is_logged_in']=0;
		            $row['is_currently_loggedin']=0;
		            
		            return (object)$row;
		        }
			//  }
    }//end of function
    
    
}//final

?>