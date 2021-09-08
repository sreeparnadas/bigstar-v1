<?php
class Purchase_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }
    function insert_purchase_master($purchase){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->trans_start();
            //insert into maxtable
            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
            	values('Purchase',1,?,'Pur')
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result = $this->db->query($sql, array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for Purchase_master_id');
            }

            //getting from maxtable
            $sql="select * from maxtable where id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $purchase_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,3).'-'.$financial_year;
            $return_array['purchase_id']=$purchase_id;
                $sql="insert into purchase_master (
               purchase_master_id
              ,vendor_id
              ,employee_id
              ,invoice_no
              ,purchase_date
            ) VALUES (?,?,?,?,?)";
            $result=$this->db->query($sql,array(
                $purchase_id
                ,
            ));
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

    }


}//final

?>