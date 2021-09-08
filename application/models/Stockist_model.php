<?php
class stockist_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function select_next_user_id_for_stockist(){
        $sql="select (current_value+1) current_value from maxtable where id=17";
        $result = $this->db->query($sql,array());
        $stockist_user_id = 'ST'.leading_zeroes($result->row()->current_value,4);
        return $stockist_user_id;
//        return $result->row();
    }

//    function select_next_user_id_for_stockist(){
//        $sql="select (current_value+1) as current_value from maxtable where id=last_insert_id()";
//        $result = $this->db->query($sql,array());
//
//        return $result;
////        return $result->row();
//    }

    function insert_new_stockist($stockist){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();

            //insert into maxtable
            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
            	values('stockist',1,?,'S')
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result = $this->db->query($sql, array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for sale_master');
            }
            //getting from maxtable
            $sql="select * from maxtable where id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $stockist_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,4).'-'.$financial_year;
            $stockist_user_id='ST'.leading_zeroes($result->row()->current_value,4);
            $serial_no=$result->row()->current_value;
//            adding New Bill Master

            //adding product//
            $sql="insert into stockist (
                   stockist_id
                  ,stockist_name
                  ,user_id
                  ,user_password
                  ,serial_no
                  ,inforce
                ) VALUES (?,?,?,?,?,1)";

            $result=$this->db->query($sql,array(
                $stockist_id
                ,$stockist-> stockist_name
                ,$stockist_user_id
                ,$stockist-> user_password
                ,$serial_no
            ));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }


            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
            $return_array['stockist_id']=$stockist_id;
            $return_array['user_id']=$stockist_user_id;
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();

            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of function


    function select_all_stockist(){
        $sql="select 
            stockist_id, stockist_name, user_id, user_password
            from stockist where inforce=1";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function update_stockist_details($stockist){
        $return_array=array();
        try{
            $this->db->trans_start();
            $sql="update stockist set stockist_name=?, user_id=?, user_password=? where stockist_id=?";
            $result=$this->db->query($sql,array(
            $stockist->stockist_name
            ,$stockist->user_id
            ,$stockist->user_password
            ,$stockist->stockist_id
            ));
            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding purchase master');
            }
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }
        catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();

            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $return_array['error_code']=$err->code;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of function



}//final

?>