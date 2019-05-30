<?php

/* USADO EXCLUSIVAMENTE PARA PICKLISTS E OUTROS OBJETIVOS!! */
include_once APPPATH . "models/modelBasicExtend.php";

class human_resource_model extends modelBasicExtend {

    function __construct() {

        $this->table = "HUMAN_RESOURCE";

        $this->pk_field = "cd_human_resource";
        $this->ds_field = "ds_human_resource_full";

        $this->sequence_obj = '"HUMAN_RESOURCE_cd_human_resource_seq"';

        $this->controller = 'human_resource_simple_maintenance';
        $this->orderByDefaultPL = ' ORDER BY ds_human_resource';


        $this->fieldsforGrid = array(
            'cd_human_resource',
            'ds_human_resource_full',
            'ds_human_resource',
            'dt_deactivated',
            'dt_record',
            'cd_hr_type',
            '( select ds_hr_type FROM ' . $this->db->escape_identifiers('HR_TYPE') . ' WHERE cd_hr_type =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_hr_type) as ds_hr_type',
            'cd_team',
            '( select ds_team FROM ' . $this->db->escape_identifiers('TEAM') . ' WHERE cd_team =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_team) as ds_team',
            'ds_password',
            'ds_e_mail',
            'cd_roles',
            'nr_login_mode',
            'ds_info_1',
            'ds_phone',
            'nr_staff_number',
            'nr_staff_number_responsible',
            'dt_join',
            'ds_location',
            $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_human_resource_title',
            '( select ds_department FROM ' . $this->db->escape_identifiers('DEPARTMENT') . ' WHERE cd_department =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_department) as ds_department',
            '( select ds_roles FROM ' . $this->db->escape_identifiers('ROLES') . ' WHERE cd_roles  =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_roles ) as ds_roles',
            '( select cd_notification_type FROM ' . $this->db->escape_identifiers('ROLES') . ', "NOTIFICATION_TYPE" WHERE cd_roles  =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_roles AND "NOTIFICATION_TYPE".cd_notification_type = "ROLES".cd_notification_type_default) as cd_notification_type',
            '( select ds_notification_type FROM ' . $this->db->escape_identifiers('ROLES') . ', "NOTIFICATION_TYPE" WHERE cd_roles  =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_roles AND "NOTIFICATION_TYPE".cd_notification_type = "ROLES".cd_notification_type_default) as ds_notification_type',
            '( select ds_human_resource_title FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE_TITLE') . ' WHERE cd_human_resource_title  =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_human_resource_title ) as ds_human_resource_title',
            'fl_super_user');

        $this->fieldsUpd = array("cd_human_resource", 'ds_location',  'nr_staff_number_responsible', 'nr_login_mode',  "ds_human_resource_full", "ds_human_resource", "dt_deactivated", "cd_hr_type", "ds_password", "ds_e_mail", "cd_human_resource_title", 'nr_staff_number', 'dt_join', 'ds_info_1');

        $cd_hmresource_logged = $this->session->userdata('cd_human_resource');

        $wherecontrol = " AND ( EXISTS ( select 1 
                           from " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " x 
                          WHERE x.cd_human_resource = $cd_hmresource_logged AND "
                . "     x.fl_super_user = 'Y' AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'Y' )"
                . "  OR  " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'N' ) ";


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'forcedwhere' => $wherecontrol
        );

        $this->fieldsForPLBase = array($this->pk_field, // first always PK
            '(' . $this->ds_field . ') as description ', // second is always the description showing up. on the dropdown,
            'ds_e_mail',
            'nr_staff_number as staff_number',
            '( select ds_roles FROM ' . $this->db->escape_identifiers('ROLES') . ' WHERE cd_roles  =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_roles ) as ds_roles',
            'cd_roles'
        );

        parent::__construct();
    }
    
    public function retGridJsonByPrjTypeGroup($cd_project_comments_type_group, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_project_comments_type_group, 'PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE', 'cd_project_comments_type_group', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelPrjTypeGroup($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE', "cd_project_comments_type_group", $add, $remove);
        echo $msg;
    }   

    
    public function retGridJsonByNotif($cd_notification_group, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_notification_group, 'HUMAN_RESOURCE_X_NOTIFICATION_GROUP', 'cd_notification_group', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelbyNotif($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'HUMAN_RESOURCE_X_NOTIFICATION_GROUP', "cd_notification_group", $add, $remove);
        echo $msg;
    }

    public function updatePasswordDb($cd_human_resource, $ds_password) {

        $sql = 'UPDATE ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' SET ds_password=md5(%s) where  cd_human_resource=%d';
        $ds_password = $this->cdbhelper->normalizeDataToSQL('ds_password', $ds_password);
        $cd_human_resource = $this->cdbhelper->normalizeDataToSQL('cd_hmresource', $cd_human_resource);

        $sql = sprintf($sql, $ds_password, $cd_human_resource);

        $this->cdbhelper->CIBasicQuery($sql);

        return $this->cdbhelper->trans_status();
    }

    public function importAD() {
        /* teste */
        $this->load->model('department_ldap_model', 'depldap', TRUE);
        $this->load->library('ldaphelper');

        if (!$this->ldaphelper->connect(2)) {
            die($this->ldaphelper->errormsg);
        }


        if (!$usersHK = $this->ldaphelper->searchUsers()) {
            die($this->ldaphelper->errormsg);
        };
        
        $this->ldaphelper->close();
        
        if (!$this->ldaphelper->connect(1)) {
            die($this->ldaphelper->errormsg);
        }

        if (!$usersCN = $this->ldaphelper->searchUsers()) {
            die($this->ldaphelper->errormsg);
        };
        
        $this->ldaphelper->close();

        $users  = array_merge ( $usersCN, $usersHK );

        $userPath = $this->getCdbhelper()->getSystemParameters('PATH_USER_PICTURES');


        foreach ($users as $key => $value) {
            //if ($value['cn'] == '') {
            //    continue;
            // }

            $todelete = strpos(strtolower($value['cn']), 'deleted') !== false || strpos(strtolower($value['cn']), 'deleted') !== false;

            $queryUser = $this->db->get_where('HUMAN_RESOURCE', array('ds_human_resource' => $value['account']))->result_array();
            $querydep = $this->db->get_where('DEPARTMENT_LDAP', array('ds_department_ldap' => $value['department']))->result_array();

            // if cannot find by User Login, Change to import by staff number;
            if (count($queryUser) == 0) {
                $queryUser = $this->db->get_where('HUMAN_RESOURCE', array('nr_staff_number' => $value['idemp']))->result_array();
            }

            if (count($querydep) == 0 && $value['department'] != NULL) {
                $this->depldap->insertdb($value['department'], null);
                $querydep = $this->db->get_where('DEPARTMENT_LDAP', array('ds_department_ldap' => $value['department']))->result_array();
            }
            

            $data = array(
                'ds_human_resource' => $value['account'],
                'ds_human_resource_full' => $value['fullname'],
                'ds_cn' => $value['cn'],
                'ds_password' => $value['fullname'],
                'ds_e_mail' => $value['mail'],
                'fl_ldap' => 'Y',
                'ds_phone' => $value['phonenumber'],
                'nr_staff_number' => $value['idemp'],
                'nr_login_mode' => $value['nr_login_mode']
            );

            if ($value['department'] != NULL) {
                $data['cd_department'] = $querydep[0]['cd_department'];

                if ($querydep[0]['cd_roles'] != NULL) {
                    $data['cd_roles'] = $querydep[0]['cd_roles'];
                }
            }

            if (count($queryUser) == 0) {

                if ($todelete) {
                    continue;
                }

                $code = $this->human_resource->getNextCode();
                $data['cd_human_resource'] = $code;
            } else {
                if ($todelete) {
                    $data['dt_deactivated'] = '20180101';
                }
            }


            $this->db->set($data);

            if (count($queryUser) == 0) {

                $this->db->insert('HUMAN_RESOURCE');
            } else {
                $code = $queryUser[0]['cd_human_resource'];
                $this->db->where('cd_human_resource', $code);
                $this->db->update('HUMAN_RESOURCE');
            }


            if ($value['thumb'] != '') {
                $tempFilename = $userPath . $code . '.jpg';
                file_put_contents($tempFilename, $value['thumb']);
                //chmod($tempFilename, 0777 & ~umask());
            }
            
            $arrayLocation = array();
            if (strpos($value['cn'], 'DG') !== false || strpos($value['cn'], 'HK') !== false ) {
                array_push($arrayLocation, array('recid' => 1, 'fl_checked' => 1));
            }
            
            if (strpos($value['cn'], 'ZH') !== false) {
                array_push($arrayLocation, array('recid' => 2, 'fl_checked' => 1));
            }
            
            $this->updatePrdCatCheckbox($code,$arrayLocation);
            
        }


        return array('status' => 'OK');
    }
    
    
    public function updatePrdCatCheckbox($user, $changes) {
        return $this->updRelationCheckBox($user, 'HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY', 'cd_system_product_category', $changes);
        
    }


}
