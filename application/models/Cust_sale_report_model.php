<?php
class cust_sale_report_model extends CI_Model {
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




    function get_terminal_total_sale_report($start_date,$end_date){


        $sql="select * from 
(select 
       max(user_id) as user_id
        ,max(agent_name) as agent_name
          , coalesce (terminal_id,'Grand Total') AS terminal_id
          ,max(stockist_user_id) as stockist_user_id
            , coalesce (ticket_taken_time,'Total') AS ticket_taken_time
            ,sum(amount) as amount
            ,sum(commision) as commision
            ,sum(prize_value) as prize_value
            ,sum(net_payable) as net_payable,max(record_time) as record_time

from (select 
             'digit' as game_name
             ,user_id
             ,agent_name
             ,max(st_user_id) as stockist_user_id
             ,terminal_id
            ,CAST(ticket_taken_time AS DATE) as ticket_taken_time
            ,terminal_total_sale_by_date(ticket_taken_time,terminal_id) as amount
            ,terminal_commission_by_sale_date(ticket_taken_time,terminal_id) as commision
            ,get_total_prize_value_by_date(ticket_taken_time,terminal_id) as prize_value
            ,terminal_net_payable_by_sale_date(ticket_taken_time,terminal_id) as net_payable
            ,max(record_time) as record_time
from (select * from digit_table where ticket_taken_time between ? and ?  order by record_time)as table1
            group by terminal_id,ticket_taken_time
            
            UNION
            
            select 
             'card' as game_name
             ,user_id
             ,agent_name
             ,max(st_user_id) as stockist_user_id
             ,terminal_id
            ,CAST(ticket_taken_time AS DATE) as ticket_taken_time
            ,terminal_total_card_sale_by_date(ticket_taken_time,terminal_id) as amount
            ,terminal_card_game_commission_by_sale_date(ticket_taken_time,terminal_id) as commision
            ,get_card_game_total_prize_value_by_date(ticket_taken_time,terminal_id) as prize_value
            ,terminal_card_net_payable_by_sale_date(ticket_taken_time,terminal_id) as net_payable
            ,max(record_time) as record_time
from (select * from card_table where ticket_taken_time between ? and ?  order by record_time)as table1
group by terminal_id,ticket_taken_time order by terminal_id,record_time) as table2
group by terminal_id, ticket_taken_time  with rollup) as table3 order by terminal_id,ticket_taken_time
";
        $result = $this->db->query($sql,array($start_date,$end_date,$start_date,$end_date));
        return $result;
    }


    function get_terminal_card_game_total_sale_report($start_date,$end_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;

        $sql="select 
          DATE_FORMAT(ticket_taken_time, \"%d/%m/%Y\") as ticket_taken_time
            ,terminal_total_card_sale_by_date(ticket_taken_time,?) as amount
            ,terminal_card_game_commission_by_sale_date(ticket_taken_time,?) as commision
            ,get_card_game_total_prize_value_by_date(ticket_taken_time,?) as prize_value
            ,terminal_card_net_payable_by_sale_date(ticket_taken_time,?) as net_payable
            from (select card_play_master.terminal_id as terminal_id, card_price_details.commision as commision, card_price_details.winning_price as winning_price
            , card_price_details.mrp as mrp, date(card_play_master.ticket_taken_time) as ticket_taken_time
            from card_play_details
            inner join card_play_master ON card_play_master.card_play_master_id = card_play_details.card_play_master_id
            inner join card_price_details on card_play_master.card_price_details_id = card_price_details.card_price_details_id) as table1
            where ticket_taken_time between ? and ? and terminal_id=?
            group by ticket_taken_time";
        $result = $this->db->query($sql,array(
            $person_id
        ,$person_id
        ,$person_id
        ,$person_id
        ,$start_date
        ,$end_date
        ,$person_id
        ));
        return $result;
    }



    function get_all_barcode_report_by_date($start_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;

        $sql="select 
            draw_time,meridiem,ticket_taken_time,barcode
            ,max(draw_master_id) as draw_master_id
            ,sum(game_value) as quantity
            ,sum(game_value) * max(mrp) as amount
            ,get_prize_value_of_barcode(barcode) as prize_value
            ,group_concat(row_num,col_num,'-',game_value order by row_num,col_num) as particulars
            ,max(is_claimed) as is_claimed
            from (select 
            play_details.play_master_id as barcode
            , play_master.terminal_id
            , play_details.play_series_id
            ,play_series.mrp
            , play_master.draw_master_id
            ,play_master.is_claimed
            , play_details.row_num
            , play_details.col_num
            , play_details.game_value
            , draw_master.start_time
            , draw_master.end_time as draw_time
            , draw_master.meridiem
            ,TIME_FORMAT(play_master.ticket_taken_time, '%h:%i %p') as ticket_taken_time
            from play_details
            inner join play_master ON play_master.play_master_id = play_details.play_master_id
            inner join draw_master ON draw_master.draw_master_id = play_master.draw_master_id
            inner join play_series ON play_series.play_series_id = play_details.play_series_id
            where play_master.terminal_id=? and date(play_master.ticket_taken_time)=?) as table1
            group by barcode order by draw_master_id";
        $result = $this->db->query($sql,array($person_id,$start_date));
        return $result;
    }

    function get_all_2d_draw_time_list(){
        $sql="select * from draw_master";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


//    report for card
    function get_card_game_barcode_report_by_date($start_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;

        $sql="select 
                draw_time
                ,meridiem
                ,ticket_taken_time
                ,barcode
                ,card_draw_master_id
                ,sum(game_value) as quantity
                ,sum(game_value)*max(mrp) as amount
                ,get_card_game_prize_value_of_barcode(barcode) as prize_value
                ,group_concat(row_num,col_num,'-',game_value order by row_num,col_num) as particulars
                ,max(is_claimed) as is_claimed
                from (select 
                card_play_details.card_play_master_id as barcode
                ,card_play_master.terminal_id
                , card_play_master.card_price_details_id
                ,card_price_details.mrp
                ,card_play_master.card_draw_master_id
                ,card_play_master.is_claimed
                , card_play_details.row_num
                , card_play_details.col_num
                , card_play_details.game_value
                ,card_draw_master.start_time
                , card_draw_master.end_time as draw_time
                , card_draw_master.meridiem
                ,DATE_FORMAT(card_play_master.ticket_taken_time, \"%d/%m/%Y\")as ticket_taken_time
                from card_play_details
                inner join card_play_master on card_play_details.card_play_master_id = card_play_master.card_play_master_id
                inner join card_draw_master on card_play_master.card_draw_master_id = card_draw_master.card_draw_master_id
                inner join card_price_details on card_play_master.card_price_details_id = card_price_details.card_price_details_id
                where card_play_master.terminal_id=? and card_play_details.game_value>0 and date(card_play_master.ticket_taken_time)=?) as table1
                group by barcode order by card_draw_master_id";
        $result = $this->db->query($sql,array($person_id,$start_date));
        return $result;
    }

    function fetch_all_terminal_user_id(){
        $sql="select stockist.user_id as stockist_user_id,person.user_id as user_id from person
            inner join stockist_to_person on stockist_to_person.person_id=person.person_id
            inner join stockist on stockist_to_person.stockist_id = stockist.stockist_id
            where person.person_cat_id=3
            order by stockist_to_person.stockist_id";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


    function fetch_all_stockist(){
        $sql="select * from stockist where inforce=1";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }





}//final

?>