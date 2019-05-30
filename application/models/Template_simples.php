<?php

include_once APPPATH.'models/modelBasicExtend.php';

class template_simples extends modelBasicExtend {
    
   function __construct()
   {
      // informacoes basicas da tabela!
      $this->table         = "PRODUCT_ATTRIBUTE_ITEMS";
      $this->pk_field      = "cd_product_attribute_items";
      $this->ds_field      = "ds_product_attribute_items";
      $this->sequence_obj  = '"PRODUCT_ATTRIBUTE_ITEMS_cd_product_attribute_items_seq"';
      $this->hasDeactivate = true;
      // informacoes de fields do grid!!!!
      $this->fieldsforGrid = array($this->pk_field,
                                   $this->ds_field,
                                   'dt_deactivated'
                                  );

      // opcoes de retrieve
      $this->retrOptions    = array ("fieldrecid" => $this->pk_field,
                                     //'subselects' => '',
                                     'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                                     'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                                     'json' => true,
                                    ); 

      // campos excluidos dos UPDS (especialmente por conta dos PLs)
      $this->fieldsExcludeUpd = array(); 

      parent::__construct();

   }
    
}



?>