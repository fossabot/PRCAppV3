<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class document_repository_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "DOCUMENT_REPOSITORY_TYPE";

        $this->pk_field = "cd_document_repository_type";
        $this->ds_field = "ds_document_repository_type";

        $this->sequence_obj = '"DOCUMENT_REPOSITORY_TYPE_cd_document_repository_type_seq"';

        $this->fieldsforGrid = array('cd_document_repository_type',
            'ds_document_repository_type',
            'cd_document_repository_category',
            '( select c.ds_document_repository_category from ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' c where c.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category ) as ds_document_repository_category ',
            'ds_document_repository_extension',
            'fl_generate_thumbs',
            'nr_thumbs_width',
            'nr_thumbs_height',
            'fl_thumbs_two_step',
            'ds_icon',
            'ds_mime_type',
            'dt_deactivated', 
            'fl_thumbs_high_quality',
            'fl_is_image', 
            'nr_max_size_kb');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            'stylecond' => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            'json' => true
        );

        $this->fieldsExcludeUpd = array('ds_document_repository_category');

        parent::__construct();
    }

    // busca informacoes de uma extensao especifica!!
    public function getDataByExtension($extension, $cd_document_repository_category) {
        $where = " where ds_document_repository_extension = '" . strtolower($extension) . "' AND dt_deactivated IS NULL ";
        if ($cd_document_repository_category != -1) {
          $where = $where . " and cd_document_repository_category =  " . $cd_document_repository_category;
        }

        return $this->retRetrieveArray($where);
    }

    public function getDataByUser($onlyNotSpecific = true) {

        $sqladdon = '';

        if ($onlyNotSpecific) {
            $sqladdon = " and x.fl_specific_purpose  = 'N' ";
        }

        $where = ' WHERE EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . '  x '
                . '                       WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . '                         ' . $sqladdon
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . '  x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = '.$this->session->userdata('cd_human_resource')
                . '                         ' . $sqladdon
                . '                  ) '
        ;

        return $this->retRetrieveArray($where);
    }

    public function getDataByMime($mime, $category = -1) {
        $where = ' WHERE ds_mime_type ilike \'%' . $mime . '%\' ';
        if ($category != -1) {
            $where = $where . 'AND cd_document_repository_category = ' . $category;
        }

        return $this->retRetrieveArray($where);
    }

    public function getAvailableExtensionRegEx($cd_category = -1, $onlyNotSpecific = true) {
        $addsql = '';
        if ($cd_category != -1) {
            $addsql = " and x.cd_document_repository_category = " . $cd_category;
        } else {
            if ($onlyNotSpecific) {
                $addsql = $addsql ." and x.fl_specific_purpose  = 'N' ";
            }
        }

        $query = 'select distinct ds_document_repository_extension '
                . '      from ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . ' '
                . '       WHERE EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x '
                . '                       WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . $addsql
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = '.$this->session->userdata('cd_human_resource')
                . $addsql
                . '                  ) ORDER BY ds_document_repository_extension';

        $ret = $this->getCdbhelper()->CIBasicQuery($query);
        $line = array();
        foreach ($ret->result_array() as $value) {

            array_push($line, $value['ds_document_repository_extension']);
        }

        $string = implode('|', $line);

        $string =  '/\.('.$string.')$/i';
        return $string;
    }
    
    public function getAvailableExtensionsBrowser($cd_category = -1, $onlyNotSpecific = true) {
        $addsql = '';
        if ($cd_category != -1) {
            $addsql = " and x.cd_document_repository_category = " . $cd_category;
        } else {
            if ($onlyNotSpecific) {
                $addsql = $addsql ." and x.fl_specific_purpose  = 'N' ";
            }
        }

        $query = 'select distinct ds_document_repository_extension '
                . '      from ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . ' '
                . '       WHERE EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x '
                . '                       WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . $addsql
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = '.$this->session->userdata('cd_human_resource')
                . $addsql
                . '                  ) ORDER BY ds_document_repository_extension';

        $ret = $this->getCdbhelper()->CIBasicQuery($query);
        $line = array();
        foreach ($ret->result_array() as $value) {

            array_push($line, "." . $value['ds_document_repository_extension']);
        }

        $string = implode(',', $line);

        return $string;
    }

    
 public function getAvailableExtensionsMaxSize($cd_category = -1, $onlyNotSpecific = true) {
        $addsql = '';
        if ($cd_category != -1) {
            $addsql = " and x.cd_document_repository_category = " . $cd_category;
        } else {
            if ($onlyNotSpecific) {
                $addsql = $addsql ." and x.fl_specific_purpose  = 'N' ";
            }
        }

        $query = 'select distinct ds_document_repository_extension, nr_max_size_kb '
                . '      from ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . ' '
                . '       WHERE EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x '
                . '                       WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . $addsql
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = '.$this->session->userdata('cd_human_resource')
                . $addsql
                . '                  ) ORDER BY ds_document_repository_extension';

        $ret = $this->getCdbhelper()->CIBasicQuery($query);
        $line = array();
        foreach ($ret->result_array() as $value) {

            $line[strtolower($value['ds_document_repository_extension'])] = $value['nr_max_size_kb'] * 1024  ;
        }

        return $line;
    }

    
    // uso esse pelo controle do where 
    public function selectForPL($where = '', $unionPK = "") {

        $where2 = ' AND EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x '
                . '                       WHERE x.cd_document_repository_category = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = "DOCUMENT_REPOSITORY_TYPE".cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = '.$this->session->userdata('cd_human_resource')
                . '                  ) ';

        $where = $where . $where2;


        return $this->cdbhelper->basicSelectForPL($this->table, $this->pk_field, $this->ds_field, $where, $unionPK, $this->hasDeactivate);
    }
    
    public function getAvailableExtensionsBrowserByMime($mime) {
        $addsql = '';

        $query = 'select distinct ds_document_repository_extension '
                . '      from ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_TYPE") . '  '
                . '  WHERE lower(ds_mime_type) like lower(\'%' . $mime . '%\')'
                . '   AND dt_deactivated IS NULL ';

        $ret = $this->getCdbhelper()->CIBasicQuery($query);
        $line = array();
        foreach ($ret->result_array() as $value) {

            array_push($line, "." . $value['ds_document_repository_extension']);
        }

        $string = implode(',', $line);
        
        return $string;
    }

}

?>