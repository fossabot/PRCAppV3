<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class document_repository_model extends modelBasicExtend {

    function __construct() {

        $this->table = "DOCUMENT_REPOSITORY";

        $this->pk_field = "cd_document_repository";
        $this->ds_field = "ds_document_repository";

        $this->sequence_obj = '"DOCUMENT_REPOSITORY_cd_document_repository_seq"';

        $this->fieldsforGrid = array('cd_document_repository',
            'ds_document_repository',
            'ds_original_file',
            'cd_document_repository_type',
            'cd_document_file'
        );

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //'stylecond'  => '',
            'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            'json' => true
        );

        $this->hasDeactivate = false;
        
        $this->fieldsUpd = array( "ds_document_repository", "ds_original_file", "cd_document_repository_type", "cd_document_file");
        

        $this->fullSQL = 'SELECT r.cd_document_repository, 
       r.ds_document_repository, 
       r.ds_original_file,        
       r.cd_document_repository_type,
       r.cd_document_file,
       datedbtogrid(r.dt_record) as dt_record,
       -- file
       f.ds_document_file_hash, 
       f.ds_document_file_path, 
       f.ds_document_file_thumbs_path, 
       f.ds_file_extension,
       t.ds_mime_type,
       -- type
       t.ds_document_repository_extension, 
       t.cd_document_repository_category, 
       t.fl_generate_thumbs, 
       t.nr_thumbs_width, 
       t.nr_thumbs_height, 
       t.fl_thumbs_two_step, 
       t.fl_is_image,
       t.ds_icon,
       -- category
       c.ds_document_repository_category, 
       c.cd_system_permission
       /*colpkrel*/

       
  FROM ' . $this->db->escape_identifiers('DOCUMENT_REPOSITORY') . ' r,
       ' . $this->db->escape_identifiers('DOCUMENT_FILE') . ' f,
       ' . $this->db->escape_identifiers('DOCUMENT_REPOSITORY_TYPE') . ' t,
       ' . $this->db->escape_identifiers('DOCUMENT_REPOSITORY_CATEGORY') . ' c
       /*tablerel*/
 WHERE f.cd_document_file           = r.cd_document_file
   AND t.cd_document_repository_type = r.cd_document_repository_type
   AND c.cd_document_repository_category = t.cd_document_repository_category
   /*joinrel*/';

        parent::__construct();
    }

    public function retDocumentRepository($id) {
        $sql = $this->fullSQL . ' AND r.cd_document_repository = ' . $id;
        $array = $this->cdbhelper->basicSQLArray($sql);

        if (count($array) == 0) {
            return false;
        }

        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        if (!file_exists($filename)) {
            return false;
        }

        return $filename;
    }

    public function retDocumentRepositoryThumbs($id) {
        $sql = $this->fullSQL . ' AND r.cd_document_repository = ' . $id;
        $array = $this->cdbhelper->basicSQLArray($sql);

        if (count($array) == 0) {
            return false;
        }

        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        $filenameThumb = $array['ds_document_file_thumbs_path'] . $array['ds_document_file_hash'] . '.png';
        if (file_exists($filenameThumb)) {
            return $filenameThumb;
        }

        if (file_exists($filename)) {
            return $filename;
        }


        return false;
    }

    public function retSQLArray($where = '', $orderby = '', $arrayTable = array()) {
        $sql = $this->fullSQL;

        IF ($arrayTable !== array()) {
            $sql = str_replace('/*joinrel*/', ' AND ' . $this->db->escape_identifiers($arrayTable['table']) . '.cd_document_repository = r.cd_document_repository', $sql);
            $sql = str_replace('/*tablerel*/', ', ' . $this->db->escape_identifiers($arrayTable['table']) . '', $sql);

            $col = ',' . $arrayTable['pk_field'];
            if (isset($arrayTable['tp_field'])) {
                $col = $col . ',' . $arrayTable['tp_field'];

                $s = '(select ' . $arrayTable['tp_field_ds'] . ' from ' . $this->db->escape_identifiers($arrayTable['tp_table']) . ' WHERE ' . $arrayTable['tp_field'] . ' = ' . $this->db->escape_identifiers($arrayTable['table']) . '.' . $arrayTable['tp_field'] . ') as tp_desc ';
                $col = $col . ',' . $s;
                
                $s = '(select ' . $arrayTable['tp_field_ds'] . ' from ' . $this->db->escape_identifiers($arrayTable['tp_table']) . ' WHERE ' . $arrayTable['tp_field'] . ' = ' . $this->db->escape_identifiers($arrayTable['table']) . '.' . $arrayTable['tp_field'] . ') as  ' . $arrayTable['tp_field_ds'];
                $col = $col . ',' . $s;
                
                $col = $col . ',' . $arrayTable['pk_field'] . ' as recid';
            }

            if (isset($arrayTable['addon_fields'])) {
                foreach ($arrayTable['addon_fields'] as $key => $value) {
                    $col = $col . ',' . $value;
                }
            }



            $sql = str_replace('/*colpkrel*/', $col, $sql);
        }
        /*    return array('table' => 'PRODUCT_DOCUMENT_REPOSITORY',
          'rel_field' => 'cd_product',
          'pk_field' => 'cd_product_document_repository',
          'tp_field' => 'cd_shoe_something'
          ); */

        return $this->cdbhelper->basicSQLArray($sql . ' ' . $where . ' ' . $orderby);
    }

    public function retSQLJson($where = '', $orderby = '') {
        $sql = $this->fullSQL;

        echo ($sql . ' ' . $where . ' ' . $orderby);

        return $this->cdbhelper->basicSQLJson($sql . ' ' . $where . ' ' . $orderby);
    }

    public function retSqlArrayByUser($where, $orderby = '', $arrayTable = array()) {

        $where1 = ' AND ( EXISTS ( SELECT 1 '
                . '                       FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x '
                . '                       WHERE x.cd_document_repository_category = t.cd_document_repository_category '
                . '                         AND x.cd_system_permission IS NULL'
                . '                  ) '
                . 'OR EXISTS ( SELECT 1 '
                . '              FROM ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . ' x, '
                . '                    ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' j,'
                . '                    ' . $this->db->escape_identifiers("JOBS_SYSTEM_PERMISSION") . ' p '
                . '             WHERE x.cd_document_repository_category = t.cd_document_repository_category '
                . '               AND x.cd_system_permission = p.cd_system_permission'
                . '               AND j.cd_jobs              = p.cd_jobs '
                . '               AND j.cd_human_resource    = ' . $this->session->userdata('cd_human_resource') . ' '
                . '                  ) '
                . '            ) ' . $where;

        return $this->retSQLArray($where1, $orderby, $arrayTable);
    }

    public function insertdbDR($ds_document_repository, $ds_original_file, $cd_document_repository_type, $cd_document_file
    ) {

        $code = $this->cdbhelper->getNextCode($this->sequence_obj);

        $data = array(
            'cd_document_repository' => $code,
            'ds_document_repository' => $ds_document_repository,
            'ds_original_file' => $ds_original_file,
            'cd_document_repository_type' => $cd_document_repository_type,
            'cd_document_file' => $cd_document_file
        );

        $sql = $this->db->insert_string('DOCUMENT_REPOSITORY', $data);

        /*
          $sql = 'INSERT INTO ' . $this->db->escape_identifiers('DOCUMENT_REPOSITORY') . '
          (
          cd_document_repository,
          ds_document_repository,
          ds_original_file,
          cd_document_repository_type,
          cd_document_file
          )
          VALUES (%s,
          \'%s\',
          \'%s\',
          %s,
          \'%s\'
          )';

          $sql = sprintf($sql, $code, $ds_document_repository, $ds_original_file, $cd_document_repository_type, $cd_document_file);
         */

        $this->getCdbhelper()->CIBasicQuery($sql);

        return $code;
    }

    public function getExtensions() {
        return $this->cdbhelper->basicSQLJson($sql . ' ' . $where . ' ' . $orderby);
    }

    public function getTableInfo($id) {
        switch ($id) {
            case 1:

                return array('table' => 'RFQ_ITEM_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_rfq_item',
                    'pk_field' => 'cd_rfq_item_document_repository');
                break;

            case 2:

                return array('table' => 'RFQ_SUPPLIER_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_rfq_supplier',
                    'pk_field' => 'cd_rfq_supplier_document_repository');
                break;

            case 3:

                return array('table' => 'RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_rfq',
                    'pk_field' => 'cd_rfq_quote_data_document_repository');
                break;

            case 4:

                $retArray = array('table' => 'EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_equipment_design',
                    'pk_field' => 'cd_equipment_design_document_repository',
                    'tp_field' => 'cd_equipment_design_document_repository_type',
                    'tp_field_ds' => 'ds_equipment_design_document_repository_type',
                    'tp_table' => 'EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE');

                $this->load->model('docrep/equipment_design_document_repository_type_model', 'tpmodel', TRUE);
                $rr = $this->tpmodel->selectForPLWithOrder('', '', 'ORDER BY fl_default DESC, ds_equipment_design_document_repository_type ASC ');
                $arrdata = array();
                foreach ($rr as $key => $value) {
                    array_push($arrdata, array('id' => $value['recid'], 'text' => $value['description']));
                }

                $retArray['tp_options'] = $arrdata;

                return $retArray;

                break;

            case 5:
                return array('table' => 'COURSE_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_course',
                    'pk_field' => 'cd_course_document_repository');
                break;

            case 6:
                return array('table' => 'COURSE_SCHEDULE_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_course_schedule',
                    'pk_field' => 'cd_course_schedule_document_repository');
                break;

            case 7:
                $retArray = array('table' => 'PROJECT_MODEL_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_project_model',
                    'pk_field' => 'cd_project_model_document_repository',
                    'tp_field' => 'cd_project_model_document_repository_type',
                    'tp_field_ds' => 'ds_project_model_document_repository_type',
                    'tp_table' => 'PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE',
                    'addon_fields' => array("(CASE WHEN fl_main = 'Y' THEN 1 ELSE 0 END) as fl_main"));

                $this->load->model('docrep/project_model_document_repository_type_model', 'tpmodel', TRUE);
                $rr = $this->tpmodel->selectForPLWithOrder('', '', 'ORDER BY fl_default DESC, ds_project_model_document_repository_type ASC ');
                $arrdata = array();
                foreach ($rr as $key => $value) {
                    array_push($arrdata, array('id' => $value['recid'], 'text' => $value['description']));
                }

                $retArray['tp_options'] = $arrdata;

                return $retArray;

                break;

            case 8:
                $retArray = array('table' => 'PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_project_build_schedule',
                    'pk_field' => 'cd_project_build_schedule_document_repository',
                    'tp_field' => 'cd_project_model_document_repository_type',
                    'tp_field_ds' => 'ds_project_model_document_repository_type',
                    'tp_table' => 'PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE');

                $this->load->model('docrep/project_model_document_repository_type_model', 'tpmodel', TRUE);
                $rr = $this->tpmodel->selectForPLWithOrder('', '', 'ORDER BY fl_default DESC, ds_project_model_document_repository_type ASC ');
                $arrdata = array();
                foreach ($rr as $key => $value) {
                    array_push($arrdata, array('id' => $value['recid'], 'text' => $value['description']));
                }

                $retArray['tp_options'] = $arrdata;

                return $retArray;

                break;

            case 9:
                return array('table' => 'RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_rfq_item_supplier_sample_request',
                    'pk_field' => 'cd_rfq_item_supplier_sample_request_document_repository');
                break;

            case 10:
                $retArray = array('table' => 'TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY',
                    'rel_field' => 'cd_tr_test_request_work_order_sample',
                    'pk_field' => 'cd_tr_test_request_work_order_sample_document_repository',
                    'tp_field' => 'cd_project_model_document_repository_type',
                    'tp_field_ds' => 'ds_project_model_document_repository_type',
                    'tp_table' => 'PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE',
                    'addon_fields' => array("(CASE WHEN fl_main = 'Y' THEN 1 ELSE 0 END) as fl_main"));

                $this->load->model('docrep/project_model_document_repository_type_model', 'tpmodel', TRUE);
                $rr = $this->tpmodel->selectForPLWithOrder('', '', 'ORDER BY fl_default DESC, ds_project_model_document_repository_type ASC ');

                $arrdata = array();
                foreach ($rr as $key => $value) {
                    array_push($arrdata, array('id' => $value['recid'], 'text' => $value['description']));
                }

                $retArray['tp_options'] = $arrdata;

                return $retArray;

                break;



            default:
                break;
        }
    }

    public function retrieveByRelation($id, $cd_code, $whereType = '') {
        $tableinfo = $this->getTableInfo($id);

        $where = ' AND EXISTS ( SELECT 1 '
                . '                    FROM ' . $this->db->escape_identifiers($tableinfo['table']) . ' s'
                . '                   WHERE s.cd_document_repository = r.cd_document_repository'
                . '                     AND s.' . $tableinfo['rel_field'] . '  = ' . $cd_code
                . ' )  ' . $whereType;


        $orderby = 'order by r.dt_record desc ';


        $docs = $this->retSqlArrayByUser($where, $orderby, $tableinfo);

        return $docs;
    }

    public function retrieveBySpecificField($id, $field, $cd_code, $whereType = '') {
        $tableinfo = $this->getTableInfo($id);

        $where = ' AND EXISTS ( SELECT 1 '
                . '                    FROM ' . $this->db->escape_identifiers($tableinfo['table']) . ' s'
                . '                   WHERE s.cd_document_repository = r.cd_document_repository'
                . '                     AND s.' . $field . '  = ' . $cd_code
                . ' )  ' . $whereType;


        $orderby = 'order by r.dt_record desc ';

        $docs = $this->retSqlArrayByUser($where, $orderby, $tableinfo);

        return $docs;
    }

    public function getFirstPicture($idDoc, $pk) {
        $retTable = $this->getTableInfo($idDoc);
        $where = ' AND ' . $this->db->escape_identifiers($retTable['table']) . '.' . $retTable['rel_field'] . ' = ' . $pk . ' AND t.fl_is_image = \'Y\'';
        $orderby = ' ORDER BY r.dt_record ASC';

        $array = $this->retSQLArray($where, $orderby, $retTable);


        if (count($array) == 0) {
            return false;
        }



        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        if (!file_exists($filename)) {
            return false;
        }

        return $filename;
    }

    public function getLastPicture($idDoc, $pk) {
        $retTable = $this->getTableInfo($idDoc);
        $where = ' AND ' . $this->db->escape_identifiers($retTable['table']) . '.' . $retTable['rel_field'] . ' = ' . $pk . ' AND t.fl_is_image = \'Y\'';
        $orderby = ' ORDER BY r.dt_record ASC';

        $array = $this->retSQLArray($where, $orderby, $retTable);

        if (count($array) == 0) {
            return false;
        }


        $array = $array[count($array) - 1];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];

        if (!file_exists($filename)) {
            return false;
        }

        return $filename;
    }

    
     public function getPictureByFile($cd_file) {
        $sql = 'SELECT * FROM "DOCUMENT_FILE" f WHERE f.cd_document_file  = ' . $cd_file;
        $array = $this->getCdbhelper()->basicSQLArray($sql);
        
        if (count($array) == 0) {
            return false;
        }

        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        $filenameThumb = $array['ds_document_file_thumbs_path'] . $array['ds_document_file_hash'] . '.png';

        if (file_exists($filenameThumb)) {
            return $filename;
        }

        return false;
    }

     public function getPictureByFileThumb($cd_file) {
        $sql = 'SELECT * FROM "DOCUMENT_FILE" f WHERE f.cd_document_file  = ' . $cd_file;
        $array = $this->getCdbhelper()->basicSQLArray($sql);
        
        if (count($array) == 0) {
            return false;
        }

        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        $filenameThumb = $array['ds_document_file_thumbs_path'] . $array['ds_document_file_hash'] . '.png';

        if (file_exists($filenameThumb)) {
            return $filenameThumb;
        }

        return false;
    }

    
    public function getFirstPictureThumb($idDoc, $pk) {
        $retTable = $this->getTableInfo($idDoc);
        $where = ' AND ' . $this->db->escape_identifiers($retTable['table']) . '.' . $retTable['rel_field'] . ' = ' . $pk . ' AND t.fl_is_image = \'Y\'';
        $orderby = ' ORDER BY r.dt_record ASC';

        $array = $this->retSQLArray($where, $orderby, $retTable);


        if (count($array) == 0) {
            return false;
        }



        $array = $array[0];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        $filenameThumb = $array['ds_document_file_thumbs_path'] . $array['ds_document_file_hash'] . '.png';

        if (file_exists($filenameThumb)) {
            return $filenameThumb;
        }

        if (file_exists($filename)) {
            return $filename;
        }


        return false;
    }

    public function getLastPictureThumb($idDoc, $pk) {
        $retTable = $this->getTableInfo($idDoc);
        $where = ' AND ' . $this->db->escape_identifiers($retTable['table']) . '.' . $retTable['rel_field'] . ' = ' . $pk . ' AND t.fl_is_image = \'Y\'';
        $orderby = ' ORDER BY r.dt_record ASC';

        $array = $this->retSQLArray($where, $orderby, $retTable);

        if (count($array) == 0) {
            return false;
        }


        $array = $array[count($array) - 1];
        $filename = $array['ds_document_file_path'] . $array['ds_document_file_hash'] . '.' . $array['ds_file_extension'];
        $filenameThumb = $array['ds_document_file_thumbs_path'] . $array['ds_document_file_hash'] . '.png';

        if (file_exists($filenameThumb)) {
            return $filenameThumb;
        }

        if (file_exists($filename)) {
            return $filename;
        }


        return false;
    }

    // function that will update the document repository and the relation table.
    public function updateGridRelData($data, $relmodel) {
        if (is_string($data)) {
            $data = json_decode($data);
        }
        $data = (array) $data;
        $docData = array();
        
        
        
        foreach ($data as $key => $value) {
           
            if ($value['cd_document_repository'] < 0) {
                $next = $this->getNextCode();
                $data[$key]['cd_document_repository'] = $next;
                $value['cd_document_repository'] = $next;
            };
            
            
            
            if (isset($value['tp_field'])){
                $data[$key]['cd_document_type'] = $value['tp_field'];
                $value['cd_document_type']      = $value['tp_field'];
            }
            
            $dataToAdd = $value;
            $dataToAdd['recid'] = $value['cd_document_repository'];
            array_push($docData, $dataToAdd);
        }

        $ret = $this->updateGridData($docData);
        if ($ret != 'OK') {
            return $ret;
        }

        

        $ret = $relmodel->updateGridData($data);
        return $ret;

        // the recid is for the reltable.
        // the others come with the name (cd_document_file);
        // the others come with the name (cd_document_repository);
    }

    public function updateGridRelDataFromField($col, $data, $relmodel) {
        if (is_string($data)) {
            $data = json_decode($data);
        }
        $data = (array) $data;
        $toUpdate = array();
        foreach ($data as $key => $value) {
            if (isset($value[$col])) {
                foreach ($value[$col] as $key1 => $value1) {
                    array_push($toUpdate, $value1);
                }
            }
        }
        return $this->updateGridRelData($toUpdate, $relmodel);
    }

//retSQLArray
}

?>