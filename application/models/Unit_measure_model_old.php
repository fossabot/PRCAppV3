<?php

class unit_measure_model extends CI_Model{
    
    var $table, 
        $pk_field,
        $ds_field,
        $sequence_obj, 
        $hasDeactivate = false,
        $fieldsforGrid,
        $retrOptions,
        $fieldsExcludeUpd;
        

    
    function __construct()
    {
        parent::__construct();
        
        // informacoes basicas da tabela!
        $this->table = "UNIT_MEASURE";
        $this->pk_field = "cd_unit_measure";
        $this->ds_field = "ds_unit_measure";
        $this->sequence_obj = '"COUNTRY_cd_country_seq"';
        
        // informacoes de fields do grid!!!!
        $this->fieldsforGrid = array('cd_unit_measure',
                                     'ds_unit_measure',
                                     'ds_unit_measure_short',
                                     'ds_unit_measure_symbol',
                                     'nr_factor_for_convertion',
                                     'cd_unit_measure_type',
                                     ' ( SELECT ds_unit_measure_type FROM "UNIT_MEASURE_TYPE" where cd_unit_measure_type = "UNIT_MEASURE".cd_unit_measure_type ) as ds_unit_measure_type ',
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
       
    }
    
    
    public function selectdb($where='', $order='') {
       
       return $this->cdbhelper->basicSelectDb($this->table, $where, $order);
        
    }
    
    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function selectForPL ($where = '', $unionPK = "") {
        return $this->cdbhelper->basicSelectForPL ($this->table, $this->pk_field, $this->ds_field, $where, $unionPK, $this->hasDeactivate);
    }
    
    public function insertdb ($desc, $dt_deactivated) {
        return $this->cdbhelper->basicInsertDb($this->table, $this->ds_field, $desc, $dt_deactivated);
    }


    public function updatedb ($id, $desc, $dt_deactivate) {
        return $this->cdbhelper->basicUpdateDb ($this->table, $this->pk_field, $this->ds_field, $id, $desc, $dt_deactivate);
    }
    
    public function updateGridData($array) {
        return $this->cdbhelper->updateGridData($this->table, $this->pk_field, $array, $this->fieldsExcludeUpd);
    }
    
    public function deleteGridData($array) {
        return $this->cdbhelper->deleteGridData($this->table, $this->pk_field, $array);
    }


    public function recordExists($id) {
        return $this->cdbhelper->recordExists($this->table, $this->pk_field, $id);
    }
    
    public function retRetrieveGridJson($where="") {
      $ret =  $this->cdbhelper->basicSelectDb($this->table, $where, " order by 2", $this->retrOptions);
      return $ret;
        
    }
    
 
    

    public function retRetrieveJson($where="") {
      
           
      $ret =  $this->cdbhelper->basicSelectDb($this->table, $where, " order by 2");
      return json_encode($ret);
        
    }
    
    
    public function retInsJson() {
       return $this->cdbhelper->basicW2ArrayIns($this->sequence_obj);
    }
    
    public function hasDeactivate() {
       return $this->hasDeactivate;
    }
    
}



?>