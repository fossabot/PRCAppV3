<?php
/**
 * This controller adapt to bootstrap-progressbar.min.js to uploading big file by dividing
 * The usage example please see system_feedback_comments_view.php
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class file_upload extends controllerBasicExtend {
    public $uploader;

    function __construct() {
        parent::__construct();
        $this->uploader = $this->session->userdata('cd_human_resource');
    }

    public function index() {
        $file_dir = $_FILES['data']['tmp_name'];
        $info = $_POST;
        $name = $info["name"];
        $total = $info["total"];
        $index = $info["index"];
        $tmp_directory = sys_get_temp_dir();
        $tmp_save = $tmp_directory . '/' . $this->uploader . '_' . $index;
        rename($file_dir, $tmp_save);

        if ($total == $index) {
            $file_name = $tmp_directory . '/' . $name;
            if (file_exists($file_name)) unlink($file_name);
            $file = fopen($file_name, 'wb');
            for ($i = 1; $i <= $total; $i++) {
                $cachefilename = $tmp_directory . '/' . $this->uploader . '_' . $i;
                $cachefile = fopen($cachefilename, 'rb');
                if ($content = fread($cachefile, filesize($cachefilename))) {
                    fwrite($file, $content);
                }
                fclose($cachefile);
                unlink($cachefilename);
            }
            fclose($file);
        }
        exit(json_encode(['success' => true, 'msg' => $index]));

    }

}
