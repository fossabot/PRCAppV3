<?php

include_once 'modelBasicExtend.php';
  

class unit_measure_model extends modelBasicExtend {
    

    function __construct()
    {
        
        // informacoes basicas da tabela!
        $this->hasDeactivate = false;
        $this->table = "UNIT_MEASURE";
        $this->pk_field = "cd_unit_measure";
        $this->ds_field = "ds_unit_measure";
        $this->sequence_obj= 'unit_measure_cd_unit_measure_seq';
        $this->controller = 'unit_measure';
        // informacoes de fields do grid!!!!
        $this->fieldsforGrid = array('cd_unit_measure',
                                     'ds_unit_measure',
                                     'ds_unit_measure_short',
                                     'ds_unit_measure_symbol',
                                     'nr_factor_for_convertion',
                                     'cd_unit_measure_type',
                                     ' ( SELECT ds_unit_measure_type FROM ' . $this->db->escape_identifiers('UNIT_MEASURE_TYPE') . ' where cd_unit_measure_type = ' . $this->db->escape_identifiers('UNIT_MEASURE') . '.cd_unit_measure_type ) as ds_unit_measure_type ',
                                     'cd_unit_measure_lenght_base',
                                     ' ( SELECT a.ds_unit_measure FROM "UNIT_MEASURE" a where a.cd_unit_measure = "UNIT_MEASURE".cd_unit_measure_lenght_base ) as ds_unit_measure_lenght_base'
                                     );
        
       // opcoes de retrieve
       $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                                    //'subselects' => '',
                                    'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                                    //'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                                    'json' => true,
                                   );
                            
               
       // campos excluidos dos UPDS (especialmente por conta dos PLs)
       $this->fieldsExcludeUpd = array('ds_unit_measure_type', 'ds_unit_measure_lenght_base'); 
       
        $this->fieldsForPLBaseDD = array( $this->pk_field,
            'ds_unit_measure_short',
            '( SELECT fl_is_length FROM "UNIT_MEASURE_TYPE" where cd_unit_measure_type = "UNIT_MEASURE".cd_unit_measure_type ) as  fl_is_length',
            ' cd_unit_measure_type'
            );

       
       parent::__construct();

    }
    
    
   public function selectForPL ($where = '', $unionPK = "") {
       $descJoin =  '( SELECT fl_is_length FROM ' . $this->db->escape_identifiers('UNIT_MEASURE_TYPE') . ' where cd_unit_measure_type = ' . $this->db->escape_identifiers('UNIT_MEASURE') . '.cd_unit_measure_type ) as  fl_is_length, cd_unit_measure_type  '; 
       
      return $this->cdbhelper->basicSelectForPL ($this->table, $this->pk_field,$this->ds_field, $where, $unionPK, $this->hasDeactivate, $descJoin);
   }

    
}



?>