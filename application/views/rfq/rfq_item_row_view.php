<?php if ($actualStep == 'ToTeamApproval' || $actualStep == 'ToReleaseToTeam') {
    $ro = 'N';
    $pld = 'PLD';
} else {
    $ro = 'Y';
    $pld = 'PL';
}

if ($choosingSupplier) {
    if ($nr_count_quote > 0 && ($nr_qtty_to_buy == 0 || $nr_qtty_to_buy == NULL)) {
        $style = "";
        $rwqtty = 'N';
    } else {
        $style = "display:none;";
        $rwqtty = 'Y';
    }

    $htmlb = '<button type="button" class="btn btn-info btn-sm" id="autosup' . $cd_rfq_item . '" style="width: 84px;; font-size: 10px;padding: 3px;' . $style . '"  onclick="dsFormRfqSheetObject.autoSupplier(' . $cd_rfq_item . '); return false;">Select Cheapest</button>';

} else {
    $htmlb = '';
    $rwqtty = 'Y';
}


?>


<tr id='rfqitem<?php echo($cd_rfq_item) ?>' class="purallrows" style="margin-top: 3px;">
    <td>
        <div class="btn-group-vertical btnArea">
            <button type="button" class="btn btn-addon btn-info btnrfqsupplier" data-toggle="tooltip"
                    title="<?php echo($opensupplier) ?>"
                    onclick="dsFormRfqSheetObject.openSuppliersItems(<?php echo($cd_rfq_item) ?>); return false;"><i
                        class='fa fa-money'></i></button>
            <button type="button" class="btn btn-addon btn-info" data-toggle="tooltip" title="<?php echo($documents) ?>"
                    onclick="dsFormRfqSheetObject.openDocRep(<?php echo($cd_rfq_item) ?>); return false;"><i
                        class='fa fa-file-archive-o'></i></button>
            <button type="button" class="btn btn-addon btn-danger btnrfqdel" data-toggle="tooltip"
                    title="<?php echo($delete) ?>"
                    onclick="dsFormRfqSheetObject.deleteItem(<?php echo($cd_rfq_item) ?>); return false;"><i
                        class='fa fa-trash-o'></i></button>
        </div>
    </td>
    <td>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" data-toggle="tooltip"   data-container="body"  title="<?php hecho($ds_equipment_design) ?>"
                    plcode="<?php echo($cd_equipment_design) ?>" value="<?php hecho($ds_equipment_design) ?>"
                    fieldname="ds_equipment_design" id="ds_equipment_design_<?php echo($cd_rfq_item) ?>_form"
                    placeholder="<?php echo($formTrans_cd_equipment_design) ?>" mask="<?php echo($pld) ?>"
                    model="<?php echo($this->encodeModel('rfq/equipment_design_model')); ?>"
                    fieldname="ds_equipment_design" code_field="cd_equipment_design" relid="-1" relCode="-1" type="text"
                    must="Y" ro="<?php echo($ro) ?>" min="3"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($ds_equipment_design_code_complement) ?>"
                    fieldname="ds_equipment_design_code_complement"
                    id="ds_equipment_design_code_complement_<?php echo($cd_rfq_item) ?>_form" mask="c"
                    ro="<?php echo($ro) ?>"></div>
        <div><input type="text" style="width: 100%"  class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>"  data-toggle="tooltip" data-container="body"    title="<?php hecho($ds_equipment_design_desc_complement) ?>"
                    value="<?php hecho($ds_equipment_design_desc_complement) ?>"
                    fieldname="ds_equipment_design_desc_complement"
                    id="ds_equipment_design_desc_complement_<?php echo($cd_rfq_item) ?>_form" mask="c"
                    ro="<?php echo($ro) ?>"></div>
    </td>
    <td>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" plcode="<?php echo($cd_rfq_request_type) ?>"
                    value="<?php hecho($ds_rfq_request_type) ?>" fieldname="ds_rfq_request_type"
                    id="ds_rfq_request_type_<?php echo($cd_rfq_item) ?>_form"
                    placeholder="<?php echo($formTrans_cd_rfq_request_type) ?>" mask="PLD"
                    model="<?php echo($this->encodeModel('rfq/rfq_request_type_model')); ?>"
                    fieldname="ds_rfq_request_type" code_field="cd_rfq_request_type" relid="-1" relCode="-1" type="text"
                    must="Y"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($ds_brand) ?>" fieldname="ds_brand"
                    id="ds_brand_<?php echo($cd_rfq_item) ?>_form" mask="c"></div>
    </td>
    <td>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($nr_qtty_quote) ?>" fieldname="nr_qtty_quote"
                    id="nr_qtty_quote_<?php echo($cd_rfq_item) ?>_form" mask="I"></div>
        <div><input type="text" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" plcode="<?php echo($cd_unit_measure) ?>"
                    value="<?php hecho($ds_unit_measure) ?>" fieldname="ds_unit_measure"
                    id="ds_unit_measure_<?php echo($cd_rfq_item) ?>_form" mask="PLD"
                    model="<?php echo($this->encodeModel('unit_measure_model')); ?>" fieldname="ds_unit_measure"
                    code_field="cd_unit_measure" relid="-1" relCode="-1" type="text"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($nr_estimated_annual) ?>"
                    fieldname="nr_estimated_annual" id="nr_estimated_annual_<?php echo($cd_rfq_item) ?>_form" mask="I">
        </div>

        <input type="text" style="width: 100%" class="form-control input-sm hidden" order="<?php echo($cd_rfq_item) ?>"
               indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($nr_count_quote) ?>" fieldname="nr_count_quote"
               id="nr_count_quote_<?php echo($cd_rfq_item) ?>_form" mask="I">


    </td>


    <td><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
               indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($dt_deadline) ?>"   fieldname="dt_deadline"
               id="dt_deadline_<?php echo($cd_rfq_item) ?>_form"></td>
    <td>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($ds_website) ?>" fieldname="ds_website"
                    id="ds_website_<?php echo($cd_rfq_item) ?>_form" mask="c" type="text" maxlength=""></div>
        <div><input type="checkbox" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($fl_online) ?>" fieldname="fl_online"
                    id="fl_online_<?php echo($cd_rfq_item) ?>_form" mask="CHK" ></div>
    </td>
    <td><textarea style="width: 100%" style="width: 100%" class="form-control input-sm"
                  order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>" fieldname="ds_reason_buy"
                  id="ds_reason_buy_<?php echo($cd_rfq_item) ?>_form" mask="c" type="text"
                  rows="3"><?php hecho($ds_reason_buy) ?></textarea></td>
    <td><textarea style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                  indexrs="<?php echo($cd_rfq) ?>" fieldname="ds_remarks"
                  id="ds_remarks_<?php echo($cd_rfq_item) ?>_form" mask="c" type="text"
                  rows="3"><?php hecho($ds_remarks) ?></textarea></td>
    <td><img originalsrc="docrep/general_document_repository/getFirstPictureThumbsSrc/1/<?php echo($cd_rfq_item) ?>"
             style="width: 100%; height: auto; margin: 0 auto" id="image_<?php echo($cd_rfq_item) ?>"></td>

    <td>
        <div><input type="checkbox" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($fl_need_sample) ?>" fieldname="fl_need_sample"
                    id="fl_need_sample_<?php echo($cd_rfq_item) ?>_form" mask="CHK"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($dt_supplier_visit_deadline) ?>"
                    fieldname="dt_supplier_visit_deadline"
                    id="dt_supplier_visit_deadline_<?php echo($cd_rfq_item) ?>_form" mask="D"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($ds_sample_info) ?>" fieldname="ds_sample_info"
                    id="ds_sample_info_<?php echo($cd_rfq_item) ?>_form" mask="t" ro="Y"></div>
    </td>


    <td>
        <div><input type="checkbox" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($fl_buy) ?>" fieldname="fl_buy"
                    id="fl_buy_<?php echo($cd_rfq_item) ?>_form" mask="CHK" ro="Y"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm" order="<?php echo($cd_rfq_item) ?>"
                    indexrs="<?php echo($cd_rfq) ?>" value="<?php hecho($nr_qtty_to_buy) ?>" fieldname="nr_qtty_to_buy"
                    id="nr_qtty_to_buy_<?php echo($cd_rfq_item) ?>_form" mask="I" ro="<?php echo($rwqtty) ?>"></div>
        <div><?php echo($htmlb) ?></div>
    </td>

    <?php if ($canFinance) { ?>
        <td>
            <div><input type="text" style="width: 100%" class="form-control input-sm"
                        order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
                        value="<?php hecho($ds_supplier) ?>" fieldname="ds_supplier"
                        id="ds_supplier_<?php echo($cd_rfq_item) ?>_form" mask="c" ro="Y"></div>
            <div><input type="text" style="width: 100%" class="form-control input-sm"
                        order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
                        value="<?php hecho($nr_total_default_currency) ?>" fieldname="nr_total_default_currency"
                        id="nr_total_default_currency_<?php echo($cd_rfq_item) ?>_form" mask="N;10.2" ro="Y"></div>
            <div><input type="text" style="width: 100%" class="form-control input-sm"
                        order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
                        value="<?php hecho($ds_reason_to_choose_supplier) ?>" fieldname="ds_reason_to_choose_supplier"
                        id="ds_reason_to_choose_supplier_<?php echo($cd_rfq_item) ?>_form" mask="c" ro="Y"></div>
        </td>

        <td><textarea style="width: 100%" style="width: 100%" class="form-control input-sm"
                      order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>" fieldname="ds_dep_cost"
                      id="ds_dep_cost_<?php echo($cd_rfq_item) ?>_form" mask="c" type="text" rows="3"
                      ro="Y"><?php hecho($ds_dep_cost) ?></textarea></td>
    <?php } ?>

    <td>
        <div><input type="text" style="width: 100%" class="form-control input-sm"
                    order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
                    value="<?php hecho($nr_moq) ?>" fieldname="$nr_moq"
                    id="nr_moq_<?php echo($cd_rfq_item) ?>_form" mask="c" ro="Y"></div>
        <div><input type="text" style="width: 100%" class="form-control input-sm"
                    order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
                    value="<?php hecho($nr_leadtime) ?>" fieldname="nr_leadtime"
                    id="nr_leadtime_<?php echo($cd_rfq_item) ?>_form" mask="c" ro="Y"></div>
    </td>


</tr>


<input type="text" class="hidden" order="<?php echo($cd_rfq_item) ?>" indexrs="<?php echo($cd_rfq) ?>"
       id="fl_is_new_<?php echo($cd_rfq_item) ?>_form" value="<?php hecho($sc) ?>" mask='C'>
