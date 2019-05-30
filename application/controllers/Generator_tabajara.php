<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class generator_tabajara extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        //$this->load->model('country_model', 'mainmodel', TRUE);
    }

    public function index() {

        parent::checkMenuPermission();
        $send = array();


        $this->load->view("generator_tabajara_view", $send);
    }

    function retTableInfo($tablename) {

        $sql = "
select c.column_name, 
       ('ds' || right(c.column_name, -2)) AS  column_name_desc , 
       c.column_default, 
       c.data_type,
       c.character_maximum_length,
       c.numeric_precision, 
       c.numeric_scale,
       c.ordinal_position,
        min(a.foreign_table_schema)       as foreign_table_schema,
       min(a.foreign_table_name) as foreign_table_name,
       min(a.foreign_column_name) as foreign_column_name,  
       min(a.foreigner_desc_column) as foreigner_desc_column,
       coalesce(left(right(column_default, -9), -12), '')   as sequence_obj
      
 from information_schema.columns  c 
 LEFT OUTER JOIN (SELECT distinct tc.table_name,
    kcu.column_name,
    ccu.table_schema     AS foreign_table_schema, 
    ccu.table_name AS foreign_table_name,
    ccu.column_name AS foreign_column_name,
    ( SELECT x.column_name
           FROM information_schema.columns  x
          WHERE x.table_name = ccu.table_name AND (x.data_type like '%char%'  OR x.data_type like '%text%' ) order by x.ordinal_position LIMIT 1) AS foreigner_desc_column
   FROM information_schema.table_constraints tc
     JOIN information_schema.key_column_usage kcu ON tc.constraint_name::text = kcu.constraint_name::text
     JOIN information_schema.constraint_column_usage ccu ON ccu.constraint_name::text = tc.constraint_name::text
  WHERE tc.constraint_type::text = 'FOREIGN KEY'::text
  ) a ON ( a.table_name = c.table_name AND a.column_name = c.column_name )
 
 where c.table_name = '" . $tablename . "' 
group by c.column_name, 
       c.column_default, 
       c.data_type,
       c.character_maximum_length,
       c.numeric_precision, 
       c.numeric_scale,
       c.ordinal_position
 order by c.ordinal_position
";

        RETURN $this->getCdbhelper()->basicSQLArray($sql);
    }

    function makeData() {
        //echo (print_r($_POST)); 
        $hasDeactivate = false;

        if ($_POST['password'] != 'b@t@t@') {
            die('Password Mismatching');
            return;
        }

        if (!isset($_POST['table_name'])) {
            die('Need to inform Table name');
        } else {
            $table_name = $_POST['table_name'];

            if ($table_name == '') {
                die('Need to inform Table name');
            }
        }

        if (isset($_POST['folder_name']) && $_POST['folder_name'] !== '') {
            $folder_name = $_POST['folder_name'] . '/';
        } else {
            $folder_name = '';
        }


        $infotable = $this->retTableInfo($table_name);

        if (count($infotable) == 0) {
            die('Table Not Found');
        }

        $formSufix = $_POST['formsufix'];

        $setVlr = $_POST['form_values'];
        
        $modelExclude = '      $this->fieldsExcludeUpd = array ( ';
        $modelupdFields = "\n" . '      $this->fieldsUpd = array ( ';

        $infoModel = '<?php
include_once APPPATH."models/modelBasicExtend.php";

class ' . strtolower($table_name) . '_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "' . $table_name . '";

     $this->pk_field = "' . $infotable[0]['column_name'] . '";
     $this->ds_field = "' . $infotable[1]['column_name_desc'] . '";
     $this->prodCatUnique = \'N\';

     $this->sequence_obj = \'' . $infotable[0]['sequence_obj'] . '\';
    
     $this->controller = \'' . $folder_name . strtolower($table_name) . '\';


     $this->fieldsforGrid = array(

';

        foreach ($infotable as $key => $value) {
            $infoModel = $infoModel . "\n' \"" . $table_name . "\"." . $value['column_name'] . "'";

            $modelupdFields = $modelupdFields . '"' . $value['column_name'] . '"';
            $modelupdFields = $modelupdFields . ", ";

            if ($key != count($infotable) - 1 || !is_null($value['foreign_table_name'])) {
                $infoModel = $infoModel . ", ";
            }
            if (!is_null($value['foreign_table_name'])) {

                $infoModel = $infoModel . "\n" . ' \'( select ' . $value['foreigner_desc_column'] . ' FROM "' . $value['foreign_table_name'] . '" WHERE ' . $value['foreign_column_name'] . ' =  "' . $table_name . '".' . $value['column_name'] . ') as ' . $value['column_name_desc'] . '\'';
                $modelExclude = $modelExclude . '"' . $value['column_name_desc'] . '"';

                if ($key != count($infotable) - 1) {
                    $infoModel = $infoModel . ", ";
                    $modelExclude = $modelExclude . ", ";
                }
            }

            if ($value['column_name'] == 'dt_deactivated') {
                $hasDeactivate = true;
            }
        }

        $modelExclude = $modelExclude . ' ); ';
        $modelupdFields = $modelupdFields . ' ); ';


        $infoModel = $infoModel . ' );';

        //$infoModel = $infoModel . $modelExclude . "\n";
        $infoModel = $infoModel . $modelupdFields . "\n";


        $infoModel = $infoModel . ' 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"' . $table_name . '\".dt_deactivated IS NOT NULL THEN \'color: rgb(255,0,0)\' ELSE \'\' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }';


        //echo ($infoModel);

        $infoController = '<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class ' . strtolower($table_name) . ' extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("' . $folder_name . strtolower($table_name) . '_model", "mainmodel", TRUE);
   }

   public function index() {

      parent::checkMenuPermission();


      $grid = $this->w2gridgen;
      $f = $this->cfields;
      $fm = $this->cfiltermaker;

      if (1 == 2) {
         $f = new Cfields();
         $grid = new w2gridgen();
         $fm = new cfiltermaker();
      }

      $fm = $this->cfiltermaker;';


        $infoContrGrid = '$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("' . $folder_name . strtolower($table_name) . '");

      $grid->addColumnKey();
      ';

        $infoContrFilter = '';

        $infoFormBootstrap = '';
        $infoFormTransl = '';
        foreach ($infotable as $key => $value) {
            $column_name = $value['column_name'];

            $gridDone = false;
            $foreign_table_name = $value['foreign_table_name'];
            $tablewith = '"' . $table_name . '"';
            //$tableFTitle        = ucwords(strtolower($foreign_table_name));

            $column_name_desc = $value['column_name_desc'];
            $data_type = $value['data_type'];

            if ($key == 0) {
                $tableFTitle = 'Code';
            } else {
                $tableFTitle = trim(ucwords(str_replace('_', ' ', strtolower(substr($column_name, 2)))));
            }


            $character_maximum_length = $value['character_maximum_length'];
            $numeric_precision = $value['numeric_precision'];
            $numeric_scale = $value['numeric_scale'];
            $foreigner_table_schema = $value['foreign_table_schema'];



            $foreigner_table_lower = strtolower($foreign_table_name);
            $column_filter = $tablewith . '.' . $column_name;
            $column_filter_desc = $tablewith . '.' . $column_name_desc;

            if ($foreigner_table_schema == 'public') {
                $foreig_model = strtolower($foreign_table_name) . '_model';
                $foreig_controller = strtolower($foreign_table_name);
            } else {
                $foreig_model = $foreigner_table_schema . '/' . strtolower($foreign_table_name) . '_model';
                $foreig_controller = $foreigner_table_schema . '/' . strtolower($foreign_table_name);
            }

            if ($setVlr == 'Y') {
                $vlrNormal = ' value="<?php hecho($'.$column_name.')?>" fieldname="'.$column_name.'"';
                $vlrPL     = ' plcode="<?php echo($'.$column_name.')?>"  value="<?php hecho($'.$column_name_desc.')?>" fieldname="'.$column_name_desc.'"';
            } else {
                $vlrNormal = ' fieldname="'.$column_name.'"';
                $vlrPL     = ' fieldname="'.$column_name_desc.'"';
            }

            
            $fpicklist = '$f->retTypePickList()';
            $fupper = '$f->retTypeStringAny()';
            $fPLText = '$f->retTypeStringAny()';
            $fPLInteger = '$f->retTypeInteger()';
            $fFloat = '$f->retTypeFloat()';
            $fNum = '$f->retTypeNum()';
            $fCheckbox = '$f->retTypeCheckBox()';
            $fDate = '$f->retTypeDate()';
            $transField = 'formTrans_' . $column_name;

            
            
            // array de traducao!

            $infoFormTransl = $infoFormTransl . "'$transField'=> '$tableFTitle',\n";

            if ($key == 0 || $column_name == 'dt_deactivated' || $column_name == 'dt_record') {

                if ($key == 0) {
                    $formField = $column_name . $formSufix;

                    $infoFormBootstrap = $infoFormBootstrap . '
                      ------------ ' . $tableFTitle . ' 
                    <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '"  mask="PK" >
                    </div>';
                }

                if ($column_name == 'dt_deactivated') {
                    $formField = $column_name . $formSufix;

                    $infoFormBootstrap = $infoFormBootstrap . '
                        ------------ ' . $tableFTitle . ' 
                        <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '">
                        </div>';
                }


                continue;
            }


            if (substr($column_name, 0, 3) == 'fl_') {
                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name', '$tableFTitle', '150px', $fCheckbox, true );";
                $formField = $column_name . $formSufix;

                $infoFormBootstrap = $infoFormBootstrap . '
                        ------------ ' . $tableFTitle . ' 
                        <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '" mask="CHK">
                        </div>';
                
                continue;
            }

            if (substr($column_name, 0, 3) == 'dt_') {
                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name', '$tableFTitle', '80px', $fDate, true );";

                $formField = $column_name . $formSufix;

                $infoFormBootstrap = $infoFormBootstrap . '
                        ------------ ' . $tableFTitle . ' 
                        <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '">
                        </div>';


                continue;
            }


            // definicoes do grid!!
            if (!is_null($foreign_table_name)) {
                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name_desc', '$tableFTitle', '150px', $fpicklist, array('model' => '$foreig_model', 'codeField' => '$column_name' ) );";
                $infoContrFilter = $infoContrFilter . "\n" . '$fm->' . "addPickListFilter('$tableFTitle', 'filter_$key', '$foreig_controller', '$column_filter');";


                $formField = $column_name_desc . $formSufix;

                $infoFormBootstrap = $infoFormBootstrap . '
                          ------------ ' . $tableFTitle . ' 
                        <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm"  '.$vlrPL.' id="' . $formField . '" mask="PL" model = "<?php echo ($this->encodeModel(\'' . $foreig_model . '\')); ?>" fieldname="' . $column_name_desc . '" code_field="' . $column_name . '"  relid="-1" relCode ="-1" type="text">
                        </div>';


                continue;
            }



            if ((stripos($data_type, 'char') !== FALSE ) || (stripos($data_type, 'text') !== FALSE )) {
                $infoContrFilter = $infoContrFilter . "\n" . '$fm->' . "addSimpleFilterUpper('$tableFTitle', 'filter_$key', '$column_filter_desc');";

                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name', '$tableFTitle', '150px', $fupper, array('limit' => '$character_maximum_length') );";

                $formField = $column_name . $formSufix;

                $infoFormBootstrap = $infoFormBootstrap . '
                      ------------ ' . $tableFTitle . ' 
                    <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '" mask="c" type="text" maxlength="' . $character_maximum_length . '" >
                    </div>';

                continue;
            }

            // definicoes do grid!!
            if (stripos($data_type, 'int') !== FALSE) {
                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name', '$tableFTitle', '150px', $fPLInteger, true );";

                $formField = $column_name . $formSufix;

                $infoFormBootstrap = $infoFormBootstrap . '
                      ------------ ' . $tableFTitle . ' 
                    <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '" mask="I" >
                    </div>';


                continue;
            }

            // definicoes do grid!!
            if (stripos($data_type, 'num') !== FALSE) {
                $formField = $column_name . $formSufix;

                $infoContrGrid = $infoContrGrid . "\n" . '$grid->' . "addColumn('$column_name', '$tableFTitle', '150px', $fNum, array('precision' => '$numeric_scale', 'readonly' => false));";

                $infoFormBootstrap = $infoFormBootstrap . '
                      ------------ ' . $tableFTitle . ' 
                    <label for="' . $formField . '" class="col-sm-1 control-label "><?php echo($' . $transField . ') ?>:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control input-sm"  '.$vlrNormal.' id="' . $formField . '" mask="N;' . $numeric_precision . '.' . $numeric_scale . '" >
                    </div>';

                continue;
            }
        }

        $infoController = $infoController . "\n";
        $infoController = $infoController . $infoContrFilter . "\n";
        if ($hasDeactivate) {
            $infoController = $infoController . '$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");' . "\n";
        }



        $infoController = $infoController . "\n" . "\n" . "\n";
        $infoController = $infoController . $infoContrGrid . "\n";

        if ($hasDeactivate) {
            $infoController = $infoController . '$grid->addColumnDeactivated(true);' . "\n";
        }


        $infoController = $infoController . ' 
            
       $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);     
        
        

      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript()) + $trans;


      $this->load->view("defaultView", $send);


                }
    }';


        $contrFile = APPPATH . 'controllers/'.$folder_name. ucfirst(strtolower($table_name)) . '.php';
        $modelFile = APPPATH . 'models/' .$folder_name. ucfirst(strtolower($table_name)) . '_model.php';
        
        if (!file_exists($contrFile)) {
            $myfile = fopen($contrFile, 'w');
            fwrite($myfile, $infoController);
            fclose($myfile);
            chmod($contrFile, 0777);

        }

        if (!file_exists($modelFile)) {
            $myfile2 = fopen($modelFile, 'w');
            fwrite($myfile2, $infoModel);
            fclose($myfile2);
            chmod($modelFile, 0777);
        }
//
        //
    //$contrFile = '/tmp/'.ucfirst(strtolower($table_name)).'.php';
        //file_put_contents ($contrFile, $infoController);
        //file_put_contents ($modelFile, $infoModel);


        echo ('{"model":' . json_encode($infoModel) . ', "controller" : ' . json_encode($infoController) . ', "formTrans": ' . json_encode($infoFormTransl) . ', "formInfo" : ' . json_encode($infoFormBootstrap) . '}');
    }

}

?>