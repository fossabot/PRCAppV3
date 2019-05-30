<?php
include_once APPPATH . "models/modelBasicExtend.php";

class wi_revision_model extends modelBasicExtend {

    function __construct() {

        $this->table = "WI_REVISION";

        $this->pk_field = "cd_wi_revision";
        $this->ds_field = "nr_wi_revision";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"WI_REVISION_cd_wi_revision_seq"';

        $this->controller = 'schedule/wi_revision';

        $this->fieldsforGrid = array(

            ' "WI_REVISION".cd_wi_revision',
            ' "WI_REVISION".nr_wi_revision',
            ' "WI_REVISION".cd_wi',
            '( select ds_wi_code FROM "WI" WHERE cd_wi =  "WI_REVISION".cd_wi) as ds_wi_code',
            ' "WI_REVISION".ds_comments',
            ' "WI_REVISION".dt_deactivated',
            ' "WI_REVISION".dt_record',
            ' "WI_REVISION".cd_human_resource_record',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "WI_REVISION".cd_human_resource_record) as ds_human_resource_record');
        $this->fieldsUpd = array("cd_wi_revision", "nr_wi_revision", "cd_wi", "ds_comments", "dt_deactivated", "dt_record", "cd_human_resource_record",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"WI_REVISION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        parent::__construct();

    }
}