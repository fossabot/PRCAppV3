<?php
/**
 * Created by PhpStorm.
 * User: Simon.Su
 * Date: 3/1/2019
 * Time: 11:26 AM
 */

class receivemail {

    public $ews;

    public function __construct($config = null) {
        if (empty($config)) {
            $config['user'] = "TTI.MilwaukeeLMS";
            $config['pass'] = "MiL@365.comSF";
            $config['delegate'] = "TTI.MilwaukeeLMS@ttigroup.com";
            $config['wsdl'] = 'https://webmail.ttigroup.com/EWS/Services.wsdl';
        }
        include_once APPPATH . "libraries/ews/init.php";
        $this->ews = new ExchangeClient();
        $this->ews->init($config['user'], $config['pass'], $config['delegate'], $config['wsdl']);

    }

    public function getEmail($limit = 50, $onlyunread = true, $folder = "inbox", $folderIdIsDistinguishedFolderId = true) {
        $data = $this->ews->get_messages($limit, $onlyunread, $folder, $folderIdIsDistinguishedFolderId);
        return $data;
    }

    public function getEvent($start = '2019-01-21T16:00:00Z', $end = '2019-03-21T16:00:00Z') {
        $data = $this->ews->get_events($start, $end);
        return $data;
    }

    public function sendMessage($to, $subject, $content, $bodytype = "Text", $saveinsent = true, $markasread = true, $attachments = [], $cc = false, $bcc = false) {
        $result = $this->ews->send_message($to, $subject, $content, $bodytype, $saveinsent, $markasread, $attachments, $cc, $bcc);
        return $result;
    }

    // This method doesn't work, can any person fix it?
    public function markMessageRead($messages) {
        $result = $this->ews->mark_message_as_read($messages);
        return $result;
    }

    public function deleteMessage($ItemId, $deletetype = "HardDelete") {
        $result = $this->ews->delete_message($ItemId, $deletetype);
        return $result;
    }

    public function getSubFolders($ParentFolderId = "inbox", $Distinguished = TRUE) {
        $result = $this->ews->get_subfolders($ParentFolderId, $Distinguished);
        return $result;
    }

    public function moveMessage($ItemId, $FolderId) {
        $result = $this->ews->move_message($ItemId, $FolderId);
        return $result;
    }

}