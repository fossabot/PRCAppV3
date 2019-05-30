<?php
/**
 * Created by PhpStorm.
 * User: Simon.Su
 * Date: 3/1/2019
 * Time: 2:16 PM
 */

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class emailread extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->library("receivemail", null);
    }

    public function index() {
        /** some usage example
         * $this->receivemail->getEmail(4,true,'AAAdAFRUSS5NaWx3YXVrZWVMTVNAdHRpZ3JvdXAuY29tAC4AAAAAAO+elTNniRhGv+5kXgMnhkYBAFbuwa7ITzxGlK0ecKRrWFEAAI3OEXEAAA==',false);
         * $this->receivemail->getEmail(4,true,'inbox',true);
         * $this->receivemail->getSubFolders('msgfolderroot',true);
         * $this->receivemail->moveMessage($message[1]->ItemId, $folders[0]->FolderId);
         *
         */
        $message = $this->receivemail->getEmail(4, true, 'Sent Items', true);
        //todo: deal with the specify messages to answer comment or approve issues
    }

    public function getEvent() {
        $event = $this->receivemail->getEvent();

    }

}