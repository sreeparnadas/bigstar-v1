<?php
class main_view_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }
    
     function select_current_date(){
        $sql="select get_current_date() as dt;";
        $result = $this->db->query($sql,array());
        if($result==null){
            return null;
        }else{
            return $result->row();
        }
    }
    

//
    function select_fr_value(){
        $current_date=get_current_date();
        $sql="select fr_value from draw_details where record_date=? and fr_value>0";
        $result = $this->db->query($sql,array(to_sql_date($current_date)));
        if($result==null){
            return null;
        }else{
            return $result->row();
        }

    }

    function select_sr_value(){
        $current_date=get_current_date();
        $sql="select sr_value from draw_details where record_date=? and sr_value>0";
        $result = $this->db->query($sql,array(to_sql_date($current_date)));
        if($result==null){
            return null;
        }else{
            return $result->row();
        }
    }

    function select_database_changes_status_fr(){
        $sql="select * from database_change where 
DATE_FORMAT(convert_tz(now(),@@session.time_zone,'+05:30') ,'%Y-%m-%d')=date(fr_record_time);";
        $result = $this->db->query($sql,array());
        if($result==null){
            return null;
        }else{
            return $result->row();
        }
    }
    
     function select_database_changes_status_sr(){
        $sql="select * from database_change where 
DATE_FORMAT(convert_tz(now(),@@session.time_zone,'+05:30') ,'%Y-%m-%d')=date(sr_record_time);";
        $result = $this->db->query($sql,array());
        if($result==null){
            return null;
        }else{
            return $result->row();
        }
    }
    
    
    function select_previous_result(){
        $sql="select 
DATE_FORMAT(record_time,'%d/%m/%y') as record_time,max(fr_value) as fr_value,max(sr_value) as sr_value from draw_details 
where  date(record_time)!=DATE_FORMAT(convert_tz(now(),@@session.time_zone,'+05:30') ,'%Y-%m-%d')
group by date(record_time) desc limit 10";
        $result = $this->db->query($sql,array());
        if($result==null){
            return null;
        }else{
            return $result;
        }
    }
    

}//final

?>