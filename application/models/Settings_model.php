<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class settings_model extends CI_Model {

    var $hasDeactivate = false;
    var $cd_human_resource;
    var $memKey;

    function __construct() {
        parent::__construct();
        $this->cd_human_resource = $this->session->userdata('cd_human_resource');
        $this->memKey = 'user' . $this->cd_human_resource . '_settings';
    
    }

    public function selectdb($where = '', $order = '') {

        $hm = $this->cd_human_resource;
        
        if ($this->cd_human_resource == '' ) {
            $hm = -1;
        }

        $sql = ' SELECT s.cd_system_settings as recid,
                     s.cd_system_settings, 
                     s.ds_system_settings, 
                     s.ds_system_settings_id, 
                     s.fl_initialize_on_db,
                     o.cd_system_settings_options, 
                     o.ds_system_settings_options, 
                     o.ds_option_id, 
                     o.fl_default, 
                     o.cd_system_settings,
                     s.fl_changeable_by_user



                FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS') . ' s, 
                     ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' o, 
                     ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . ' h
               WHERE h.cd_human_resource          = ?
                 AND o.cd_system_settings_options = h.cd_system_settings_options
                 AND s.cd_system_settings         = o.cd_system_settings
                  ' . $where . '

              UNION     

              SELECT s.cd_system_settings as recid,
                     s.cd_system_settings, 
                     s.ds_system_settings, 
                     s.ds_system_settings_id, 
                     s.fl_initialize_on_db,
                     o.cd_system_settings_options, 
                     o.ds_system_settings_options, 
                     o.ds_option_id, 
                     o.fl_default, 
                     o.cd_system_settings,
                     s.fl_changeable_by_user
                FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS') . ' s, 
                     ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' o
               WHERE s.cd_system_settings         = o.cd_system_settings
                 AND NOT EXISTS ( SELECT 1 
                                    FROM ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . ' h
                                   WHERE h.cd_human_resource          = ?
                                     AND h.cd_system_settings         = o.cd_system_settings
                                )
                 AND o.fl_default = \'Y\'
                  ' . $where . '
                  ' . $order;


        
        
        $q = $this->db->query($sql, array($hm, $hm));
        $array = $q->result_array();

        return $array;
    }

    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function selectForPL($where = '', $unionPK = "") {
        return $this->cdbhelper->basicSelectForPL($this->table, $this->pk_field, $this->ds_field, $where, $unionPK);
    }

    public function updateGridData($array) {
        $cd_human_resource = $this->session->userdata('cd_human_resource');

        $this->cdbhelper->trans_begin();
        $bError = false;

        foreach ($array as $row) {

            if ($this->recordExistsbyUser($cd_human_resource, $row['cd_system_settings'])) {
                $sql = 'UPDATE ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . ' set cd_system_settings_options = ' . $row['cd_system_settings_options'] . ''
                        . '   WHERE cd_human_resource  = ' . $cd_human_resource . ''
                        . '     AND cd_system_settings = ' . $row['cd_system_settings'];
            } else {
                $sql = 'INSERT INTO ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . ''
                        . ' ( cd_system_settings_options ,'
                        . '   cd_human_resource ,'
                        . '   cd_system_settings'
                        . ' )'
                        . ' values (' . $row['cd_system_settings_options'] . ', '
                        . '         ' . $cd_human_resource . ',
                           ' . $row['cd_system_settings'] . ' )';
            }

            $this->getCdbhelper()->CIBasicQuery($sql);

            if (!$this->cdbhelper->trans_status()) {
                $bError = true;
                break;
            }
        }

        if ($bError) {
            $error = $this->cdbhelper->trans_last_error();
        } else {
            $error = "OK";
            $this->cdbhelper->trans_commit();
        }

        $this->cdbhelper->trans_end();

        $this->cdbhelper->removeMemVar($this->memKey);

        return $error;
    }

    public function recordExistsbyUser($cd_human_resource, $cd_system_settings) {
        $sql = 'SELECT 1 FROM ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . ' where cd_human_resource = ' . $cd_human_resource . ' and cd_system_settings = ' . $cd_system_settings;
        $query = $this->getCdbhelper()->CIBasicQuery($sql);
        return $query->num_rows() > 0;
    }

    public function sendSettingsToDb() {
        $ret = $this->selectdb(" AND s.fl_initialize_on_db = 'Y' ");

        foreach ($ret as $row) {
            $this->db->query("select setvar('" . $row['ds_system_settings_id'] . "','" . $row['ds_option_id'] . "');");

//         $this->getCdbhelper()->setDbVars($row['ds_system_settings_id'], $row['ds_option_id']);
        }
    }

    public function getSetting($setting_id) {
        //$fromMem = $this->cdbhelper->getMemVarArray($this->memKey);
        //if (array_key_exists($setting_id, $fromMem)) {
        //   return $fromMem[$setting_id];
        //}
        // se nao existe em memoria!
        $ret_array = $this->selectdb(" AND s.ds_system_settings_id = '" . $setting_id . "' ");

        //   if ( $setting_id ===  'cd_system_languages') {
        //         die (print_r($ret_array[0]['ds_option_id']));
        //     }
        //$fromMem[$setting_id] = $ret[0]['ds_option_id'];
        //$this->cdbhelper->setMemVar($this->memKey,$fromMem );
        if (count($ret_array) == 0) {
            return '';
            //die ("Setting not found: $setting_id");
        }
        return $ret_array[0]['ds_option_id'];
    }

    public function retRetrieveJson($where = "") {

        $where = $where . " AND fl_changeable_by_user = 'Y' ";
        $ret = $this->selectdb($where, " order by 2");

        $array = array();

        foreach ($ret as $row) {
            $style = "";

            $insArray = $this->W2Array(intval($row['recid']), $row['ds_system_settings'], $row['cd_system_settings_options'], $row['ds_system_settings_options']
            );

            array_push($array, $insArray);
        }

        return json_encode($array);
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function W2Array($id, $ds_system_settings, $cd_system_settings_options, $ds_system_settings_options, $style = "") {

        $array = array('recid' => $id,
            'ds_system_settings' => rtrim($ds_system_settings),
            'ds_system_settings_options' => $ds_system_settings_options,
            'cd_system_settings_options' => $cd_system_settings_options,
            'style' => $style
        );

        return $array;
    }

    public function retConfigInformation() {
        $sql = 'select  g.ds_system_settings_group, 
        x.cd_system_settings, 
        retDescTranslated(x.ds_system_settings, null)  as ds_system_settings, 
        ds_system_settings_id, 
         a.cd_system_settings_options,  
         (CASE WHEN x.fl_translate_options = \'Y\' THEN retDescTranslated(a.ds_system_settings_options, null) ELSE a.ds_system_settings_options END ) as ds_system_settings_options,
         x.fl_type_selection,
         a.ds_option_id,
         coalesce ( ( SELECT cd_system_settings_options
                                                              FROM ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . '
                                                             WHERE cd_human_resource  = ?
                                                               AND cd_system_settings = x.cd_system_settings
                                                           ) , ( SELECT p.cd_system_settings_options  
                                                                   FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' p
                                                                  WHERE p.cd_system_settings = x.cd_system_settings
                                                                    AND p.fl_default = \'Y\'
                                                               )
                                                          ) as cd_system_settings_options_selected,
        (  select ds_option_id FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' b where b.cd_system_settings_options =   coalesce ( ( SELECT cd_system_settings_options
                                                              FROM ' . $this->db->escape_identifiers('HR_SYSTEM_SETTINGS_OPTIONS') . '
                                                             WHERE cd_human_resource  = ?
                                                               AND cd_system_settings = x.cd_system_settings
                                                           ) , ( SELECT p.cd_system_settings_options  
                                                                   FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' p
                                                                  WHERE p.cd_system_settings = x.cd_system_settings
                                                                    AND p.fl_default = \'Y\'
                                                               )
                                                          )  ) as ds_option_id_selected
                                                           
                                                             
   FROM ' . $this->db->escape_identifiers('SYSTEM_SETTINGS') . ' x,
        ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_OPTIONS') . ' a ,
        ' . $this->db->escape_identifiers('SYSTEM_SETTINGS_GROUP') . ' g,
        ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '  h
   where x.fl_changeable_by_user    = \'Y\'
     and a.cd_system_settings       = x.cd_system_settings
     and g.cd_system_settings_group = x.cd_system_settings_group
     and h.cd_human_resource        = ?
     and ( x.fl_only_for_super_users  = \'N\' OR h.fl_super_user =  \'Y\' )
     ORDER BY g.nr_order, x.nr_order';

        $q = $this->db->query($sql, array($this->cd_human_resource, $this->cd_human_resource, $this->cd_human_resource));
        //print_r($q->result_array());
        return $q->result_array();
    }

    public function getCdbhelper() {
        return $this->cdbhelper;
    }

}
