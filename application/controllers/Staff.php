<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this -> load -> model('person');
    }
    public function index()
    {
        $this->load->view('staff/index_top');
        $this->load->view('staff/index_header');
        $this->load->view('staff/index_main');
        $this->load->view('staff/index_login');
        //$this->load->view('index_slider');
        $this->load->view('staff/index_feature');
        $this->load->view('staff/index_gallery');
        $this->load->view('staff/index_parallax_window');
        $this->load->view('staff/index_pricing');
        $this->load->view('staff/index_our_team');
        $this->load->view('staff/index_contact');
        $this->load->view('staff/index_end');
    }
}
?>