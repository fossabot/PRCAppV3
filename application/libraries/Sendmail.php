<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cFields
 *
 * @author dvlpserver
 */
class sendmail {

    //put your code here

    function __construct() {

        $this->emailsAdded = array();
        $this->mailTO = array();
        $this->mailCC = array();
        $this->mailBCC = array();
        $this->mailMessage = "";
        $this->sendToSender = true;


        $this->CI = & get_instance();
        //$this->load->model('hrms/employee_model', 'emp');

        $this->CI->load->library('email');
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';


// SMTP
        $config['protocol'] = 'smtp';
        //$config['smtp_host'] = 'Webmail.ttigroup.com'; //ssl://
        $config['smtp_host'] = '10.64.16.136'; //ssl://

        //$config['smtp_host'] = 'hybrid.ttigroup.com'; //ssl://
        //$config['smtp_user'] = 'TTI.MilwaukeeLMS@ttigroup.com';
        $config['smtp_user'] = 'TTI.MilwaukeeLMS';
        $config['smtp_pass'] = 'MiL@365.comSF';
        $config['smtp_port'] = 25;

//        $config['smtp_user'] = 'TTI.MilwaukeeOMS';
//        $config['smtp_pass'] = 'BAc@360M';
//        $config['smtp_port'] = 25;
        
        
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->set_crlf("\r\n");

        $this->CI->email->initialize($config);

        $this->CI->email->clear();

        $this->errorMessage = '';

        $this->bccSender = $this->CI->session->userdata('ds_e_mail');
    }
    
    public function sendToSender($bool) {
        // removed to make sure it is sent. temporary solution until release the project screen
        //$this->sendToSender = $bool;
    }

    public function sendMail() {
        $this->CI->email->from('TTI.MilwaukeeLMS@ttigroup.com');
        //$this->CI->email->from('TTI.MilwaukeeOMS@ttigroup.com');

        if ($this->sendToSender) {
            $this->addCC($this->bccSender);
        }

        if ($this->CI->isTest) {
            $this->CI->email->to(array($this->bccSender));

            $this->mailMessage = $this->mailMessage . '<br><br><br> <strong> ------- TEST ENVIRONMENT ----- ON PRODUCTION WILL SEND TO ----</strong><br>';
            foreach ($this->mailTO as $key => $value) {
                $this->mailMessage = $this->mailMessage . "<br> TO: $value";
            }

            foreach ($this->mailCC as $key => $value) {
                $this->mailMessage = $this->mailMessage . "<br> CC: $value";
            }

            foreach ($this->mailBCC as $key => $value) {
                $this->mailMessage = $this->mailMessage . "<br> BCC: $value";
            }
            
        } else {
            $this->CI->email->to($this->mailTO);
            $this->CI->email->cc($this->mailCC);
            $this->CI->email->bcc($this->mailBCC);
        }
        
        //die ($this->mailMessage);

        $this->CI->email->message($this->mailMessage);
        $ret =  $this->CI->email->send(false);
        $this->clear();
        //$x = $this->CI->email->print_debugger( array('headers', 'subject', 'body') );
        //die ($x);
        
        return $ret ;
    }

    public function clear() {
        $this->emailsAdded = array();
        $this->mailTO = array();
        $this->mailCC = array();
        $this->mailBCC = array();
        $this->mailMessage = "";
        $this->CI->email->clear();
    }

    public function addTO($to) {

        if (array_search($to, $this->emailsAdded) !== FALSE) {
            return;
        }

        array_push($this->emailsAdded, $to);
        array_push($this->mailTO, $to);
    }

    public function addCC($to) {

        if (array_search($to, $this->emailsAdded) !== FALSE) {
            return;
        }

        array_push($this->emailsAdded, $to);
        array_push($this->mailCC, $to);
    }

    public function addBCC($to) {
        if (array_search($to, $this->emailsAdded) !== FALSE) {
            return;
        }
        array_push($this->emailsAdded, $to);
        array_push($this->mailBCC, $to);
    }

    public function setSubject($sub) {
        $this->CI->email->subject($sub);
    }

    public function setMessage($message) {
        $this->mailMessage = $message;

    }

    public function addAttachment($file, $altname = '') {
        if ($altname != '') {
            $this->CI->email->attach($file, 'attachment', $altname);
        } else {
            $this->CI->email->attach($file, 'attachment');
        }
    }

    public function addAttachmentInline($file) {
        $this->CI->email->attach($file, 'inline');
    }

}
