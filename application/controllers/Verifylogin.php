<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('human_resource', '', TRUE);
        
    }

    function index() {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            $this->load->view('login');
        } else {
            //Go to private area
            redirect('main', 'refresh');
        }
    }

    function check_database($password, $username = '') {
        $username = $this->input->post('username');
        
        $msg = $this->cdbhelper->check_database($password, $username);
        if ($msg != 'OK') {
            $this->form_validation->set_message('check_database', $msg);
            return false;
        } else {
            return true;
        }
    }
        
}

?>