<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class collect_brake_life extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("collect_brake_life_model", "mainmodel", TRUE);
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

      $fm = $this->cfiltermaker;

$fm->addSimpleFilterUpper('Stmethod', 'filter_3', '"COLLECT_BRAKE_LIFE".dsstmethod');
$fm->addSimpleFilterUpper('Rrentstatus', 'filter_7', '"COLLECT_BRAKE_LIFE".dsrrentstatus');
$fm->addSimpleFilterUpper('Stresult', 'filter_9', '"COLLECT_BRAKE_LIFE".dsstresult');
$fm->addSimpleFilterUpper('Ntrollerno', 'filter_10', '"COLLECT_BRAKE_LIFE".dsntrollerno');
$fm->addSimpleFilterUpper('Gno', 'filter_11', '"COLLECT_BRAKE_LIFE".dsgno');
$fm->addSimpleFilterUpper('Ojectno', 'filter_12', '"COLLECT_BRAKE_LIFE".dsojectno');
$fm->addSimpleFilterUpper('Imodelno', 'filter_13', '"COLLECT_BRAKE_LIFE".dsimodelno');
$fm->addSimpleFilterUpper('Mpleno', 'filter_14', '"COLLECT_BRAKE_LIFE".dsmpleno');
$fm->addSimpleFilterUpper('Stommodelno', 'filter_15', '"COLLECT_BRAKE_LIFE".dsstommodelno');
$fm->addSimpleFilterUpper('Quisitionno', 'filter_16', '"COLLECT_BRAKE_LIFE".dsquisitionno');
$fm->addSimpleFilterUpper('Quisitionperson', 'filter_18', '"COLLECT_BRAKE_LIFE".dsquisitionperson');
$fm->addSimpleFilterUpper('Ecificationunit', 'filter_20', '"COLLECT_BRAKE_LIFE".dsecificationunit');
$fm->addSimpleFilterUpper('Eremade', 'filter_21', '"COLLECT_BRAKE_LIFE".dseremade');
$fm->addSimpleFilterUpper('Pea', 'filter_22', '"COLLECT_BRAKE_LIFE".dspea');
$fm->addSimpleFilterUpper('Oductdescription', 'filter_25', '"COLLECT_BRAKE_LIFE".dsoductdescription');
$fm->addSimpleFilterUpper('Aluatedpurpose', 'filter_26', '"COLLECT_BRAKE_LIFE".dsaluatedpurpose');
$fm->addSimpleFilterUpper('Rtdescription', 'filter_27', '"COLLECT_BRAKE_LIFE".dsrtdescription');
$fm->addSimpleFilterUpper('Sttype', 'filter_28', '"COLLECT_BRAKE_LIFE".dssttype');
$fm->addSimpleFilterUpper('Te', 'filter_31', '"COLLECT_BRAKE_LIFE".dste');
$fm->addSimpleFilterUpper('Ltagehienable', 'filter_32', '"COLLECT_BRAKE_LIFE".dsltagehienable');
$fm->addSimpleFilterUpper('Ltageloenable', 'filter_34', '"COLLECT_BRAKE_LIFE".dsltageloenable');
$fm->addSimpleFilterUpper('Armtype', 'filter_36', '"COLLECT_BRAKE_LIFE".dsarmtype');
$fm->addSimpleFilterUpper('Loadhienable', 'filter_37', '"COLLECT_BRAKE_LIFE".dsloadhienable');
$fm->addSimpleFilterUpper('Loadloenable', 'filter_39', '"COLLECT_BRAKE_LIFE".dsloadloenable');
$fm->addSimpleFilterUpper('Adhienable', 'filter_41', '"COLLECT_BRAKE_LIFE".dsadhienable');
$fm->addSimpleFilterUpper('Adloenable', 'filter_43', '"COLLECT_BRAKE_LIFE".dsadloenable');
$fm->addSimpleFilterUpper('Mp1enable', 'filter_45', '"COLLECT_BRAKE_LIFE".dsmp1enable');
$fm->addSimpleFilterUpper('Mp1location', 'filter_46', '"COLLECT_BRAKE_LIFE".dsmp1location');
$fm->addSimpleFilterUpper('Mp1hienable', 'filter_47', '"COLLECT_BRAKE_LIFE".dsmp1hienable');
$fm->addSimpleFilterUpper('Mp1loenable', 'filter_49', '"COLLECT_BRAKE_LIFE".dsmp1loenable');
$fm->addSimpleFilterUpper('Mp2enable', 'filter_51', '"COLLECT_BRAKE_LIFE".dsmp2enable');
$fm->addSimpleFilterUpper('Mp2location', 'filter_52', '"COLLECT_BRAKE_LIFE".dsmp2location');
$fm->addSimpleFilterUpper('Mp2hienable', 'filter_53', '"COLLECT_BRAKE_LIFE".dsmp2hienable');
$fm->addSimpleFilterUpper('Mp2loenable', 'filter_55', '"COLLECT_BRAKE_LIFE".dsmp2loenable');
$fm->addSimpleFilterUpper('Mp3enable', 'filter_57', '"COLLECT_BRAKE_LIFE".dsmp3enable');
$fm->addSimpleFilterUpper('Mp3location', 'filter_58', '"COLLECT_BRAKE_LIFE".dsmp3location');
$fm->addSimpleFilterUpper('Mp3hienable', 'filter_59', '"COLLECT_BRAKE_LIFE".dsmp3hienable');
$fm->addSimpleFilterUpper('Mp3loenable', 'filter_61', '"COLLECT_BRAKE_LIFE".dsmp3loenable');
$fm->addSimpleFilterUpper('Mp4enable', 'filter_63', '"COLLECT_BRAKE_LIFE".dsmp4enable');
$fm->addSimpleFilterUpper('Mp4location', 'filter_64', '"COLLECT_BRAKE_LIFE".dsmp4location');
$fm->addSimpleFilterUpper('Mp4hienable', 'filter_65', '"COLLECT_BRAKE_LIFE".dsmp4hienable');
$fm->addSimpleFilterUpper('Mp4loenable', 'filter_67', '"COLLECT_BRAKE_LIFE".dsmp4loenable');
$fm->addSimpleFilterUpper('Port', 'filter_69', '"COLLECT_BRAKE_LIFE".dsport');
$fm->addSimpleFilterUpper('Arm', 'filter_70', '"COLLECT_BRAKE_LIFE".dsarm');
$fm->addSimpleFilterUpper('Librationspeed', 'filter_73', '"COLLECT_BRAKE_LIFE".dslibrationspeed');
$fm->addSimpleFilterUpper('Partment', 'filter_75', '"COLLECT_BRAKE_LIFE".dspartment');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("collect_brake_life");

      $grid->addColumnKey();
      
$grid->addColumn('subid', 'Bid', '150px', $f->retTypeInteger(), true );
$grid->addColumn('taskid', 'Skid', '150px', $f->retTypeInteger(), true );
$grid->addColumn('testmethod', 'Stmethod', '150px', $f->retTypeStringAny(), array('limit' => '100') );
$grid->addColumn('alreadytesttime', 'Readytesttime', '150px', $f->retTypeInteger(), true );
$grid->addColumn('currentstatus', 'Rrentstatus', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('currentcycle', 'Rrentcycle', '150px', $f->retTypeInteger(), true );
$grid->addColumn('testresult', 'Stresult', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('controllerno', 'Ntrollerno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('jigno', 'Gno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('projectno', 'Ojectno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('ttimodelno', 'Imodelno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('sampleno', 'Mpleno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('custommodelno', 'Stommodelno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('requisitionno', 'Quisitionno', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('requisitionperson', 'Quisitionperson', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('specification', 'Ecification', '150px', $f->retTypeInteger(), true );
$grid->addColumn('specificationunit', 'Ecificationunit', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('wheremade', 'Eremade', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('typea', 'Pea', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('ebsample', 'Sample', '150px', $f->retTypeInteger(), true );
$grid->addColumn('qualificationbuild', 'Alificationbuild', '150px', $f->retTypeInteger(), true );
$grid->addColumn('productdescription', 'Oductdescription', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('evaluatedpurpose', 'Aluatedpurpose', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('partdescription', 'Rtdescription', '150px', $f->retTypeStringAny(), array('limit' => '2000') );
$grid->addColumn('testtype', 'Sttype', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('modulus', 'Dulus', '150px', $f->retTypeInteger(), true );
$grid->addColumn('recordedbycycles', 'Cordedbycycles', '150px', $f->retTypeInteger(), true );
$grid->addColumn('note', 'Te', '150px', $f->retTypeStringAny(), array('limit' => '2000') );
$grid->addColumn('voltagehienable', 'Ltagehienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('voltageloenable', 'Ltageloenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('alarmtype', 'Armtype', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('noloadhienable', 'Loadhienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('noloadloenable', 'Loadloenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('loadhienable', 'Adhienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('loadloenable', 'Adloenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp1enable', 'Mp1enable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp1location', 'Mp1location', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('temp1hienable', 'Mp1hienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp1loenable', 'Mp1loenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp2enable', 'Mp2enable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp2location', 'Mp2location', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('temp2hienable', 'Mp2hienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp2loenable', 'Mp2loenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp3enable', 'Mp3enable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp3location', 'Mp3location', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('temp3hienable', 'Mp3hienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp3loenable', 'Mp3loenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp4enable', 'Mp4enable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp4location', 'Mp4location', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('temp4hienable', 'Mp4hienable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('temp4loenable', 'Mp4loenable', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('report', 'Port', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('alarm', 'Arm', '150px', $f->retTypeStringAny(), array('limit' => '500') );
$grid->addColumn('calibrationspeed', 'Librationspeed', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('department', 'Partment', '150px', $f->retTypeStringAny(), array('limit' => '50') );
$grid->addColumn('nr_wo_data', 'Wo Data', '150px', $f->retTypeInteger(), true );
$grid->addColumn('nr_sample', 'Sample', '150px', $f->retTypeInteger(), true );
 
            
       $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);     
        
        

      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript()) + $trans;


      $this->load->view("defaultView", $send);


                }
    }