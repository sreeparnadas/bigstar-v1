<?php
class sale_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

   function select_inforce_products(){
       $sql="select * from product
        inner join product_group ON product_group.group_id = product.group_id
        where product.inforce=1";
       $result = $this->db->query($sql,array());
       return $result;
   }



}//final

?>