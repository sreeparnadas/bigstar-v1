<?php
class Customer_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function insert_new_customer($customer){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            //$this->db->query("START TRANSACTION");
            $this->db->trans_start();
            //insert into maxtable
            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
            	values('customer',1,?,'C')
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result=$this->db->query($sql,array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for Customer_id');
            }
            //Getting from max_table
            $sql="select * from maxtable where id=last_insert_id()";
            $result=$this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $customer_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,3).'-'.$financial_year;
            $return_array['person_id']=$customer_id;
            $sql = "insert into person (
                   person_id
                  ,person_cat_id
                  ,person_name
                  ,billing_name
                  ,sex
                  ,mobile_no
                  ,phone_no
                  ,email_id
                  ,aadhar_no
                  ,pan_no
                  ,address1
                  ,address2
                  ,city
                  ,district_id
                  ,post_office
                  ,pin
                  ,gst_number
                  ,inforce
                  ,area
                  ,state_id
                ) VALUES (?,4,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1,?,?)";
            $result=$this->db->query($sql,array(
                $customer_id
            ,$customer->person_name
            ,$customer->billing_name
            ,$customer->sex
            ,$customer->mobile_no
            ,$customer->phone_no
            ,$customer->email
            ,$customer->aadhar_no
            ,$customer->pan_no
            ,$customer->address1
            ,$customer->address2
            ,$customer->city
            ,$customer->district_id
            ,$customer->post_office
            ,$customer->pin
            ,$customer->gst_number
            ,$customer->area
            ,$customer->state_id
            ));
            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding new customer');
            }
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
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
    }
    function select_customers(){
        $sql="select person.person_id
            , person.person_name
            , person.billing_name
            , person.mobile_no
            , person.phone_no
            , person.pan_no
            , person.address1
            , person.address2
            , person.city
            , person.post_office
            , person.pin
            , person.gst_number
            , person.state_id
            , states.state_name
            , districts.district_name 
            from person
            inner join states on person.state_id = states.state_id
            inner join districts on person.district_id = districts.district_id
            where person_cat_id=4";
        $result = $this->db->query($sql,array());
        return $result;
    }
    function update_customer_by_customer_id($customer){
        $return_array=array();
        try{
            $this->db->trans_start();
            //update Customer
            $sql="update person set
                person_name=?
                , billing_name=?
                , sex=?
                , mobile_no=?
                , phone_no=?
                , email_id=?
                , aadhar_no=?
                , pan_no=?
                , address1=?
                , address2=?
                , city=?
                , district_id=?
                , post_office=?
                , pin=?
                , gst_number=?
                , area=?
                , state_id=? where person_id=?";
            $result=$this->db->query($sql,array(
                $customer->person_name
            ,$customer->billing_name
            ,$customer->sex
            ,$customer->mobile_no
            ,$customer->phone_no
            ,$customer->email
            ,$customer->aadhar_no
            ,$customer->pan_no
            ,$customer->address1
            ,$customer->address2
            ,$customer->city
            ,$customer->district_id
            ,$customer->post_office
            ,$customer->pin
            ,$customer->gst_number
            ,$customer->area
            ,$customer->state_id
            ,$customer->person_id
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error updating vendor');
            }
            // adding customer completed
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully Updated';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'person model','update_person',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//End of update_customer_by_customer_id()
}//final

?>