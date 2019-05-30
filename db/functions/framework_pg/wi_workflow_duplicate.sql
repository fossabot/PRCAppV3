
CREATE OR REPLACE FUNCTION schedule.wi_workflow_duplicate (p_cd_wi bigint)
RETURNS bigint AS
$$
DECLARE

v_max_version bigint;

v_cd_wi_workflow bigint;
v_cd_wi_workflow_equipment bigint;

v_record_workflow record;
v_record_equipment record;

BEGIN

   v_cd_wi_workflow = nextval('schedule."WI_SECTION_WORKFLOW_cd_wi_section_workflow_seq"'::regclass);

   SELECT INTO v_record_workflow * from schedule."WI_SECTION_WORKFLOW" WHERE cd_wi_section_workflow = p_cd_wi LIMIT 1;
   select max(nr_wi_section_workflow_revision)+1 into v_max_version from schedule."WI_SECTION_WORKFLOW" where cd_wi_section = v_record_workflow.cd_wi_section;

   INSERT INTO schedule."WI_SECTION_WORKFLOW"
        (cd_wi_section_workflow, ds_wi_section_workflow, ds_wi_section_workflow_code, cd_wi_section_revision_type, dt_deactivated, dt_record, cd_wi_section, dt_approval, cd_test_unit,
        nr_man_power,ds_specification,ds_equipment_description,nr_wi_section_workflow_revision,nr_wi_section_workflow_revision_minor,cd_project_model,cd_project_product)
   VALUES
        (v_cd_wi_workflow,v_record_workflow.ds_wi_section_workflow, v_record_workflow.ds_wi_section_workflow_code, v_record_workflow.cd_wi_section_revision_type,
         v_record_workflow.dt_deactivated, now(), v_record_workflow.cd_wi_section, v_record_workflow.dt_approval, v_record_workflow.cd_test_unit,
        v_record_workflow.nr_man_power,v_record_workflow.ds_specification,v_record_workflow.ds_equipment_description,v_record_workflow.nr_wi_section_workflow_revision,
        v_record_workflow.nr_wi_section_workflow_revision_minor,v_record_workflow.cd_project_model,v_record_workflow.cd_project_product);

   FOR v_record_equipment IN SELECT * FROM schedule."WI_SECTION_WORKFLOW_EQUIPMENT" WHERE cd_wi_section_workflow = p_cd_wi
      LOOP
          v_cd_wi_workflow_equipment = nextval('schedule."WI_SECTION_WORKFLOW_EQUIPMENT_cd_wi_section_workflow_equipm_seq"'::regclass);

          INSERT INTO schedule."WI_SECTION_WORKFLOW_EQUIPMENT"
              (cd_wi_section_workflow_equipment, cd_equipment_design, nr_ratio, ds_notes, dt_record, cd_wi_section_workflow
              )
          VALUES
              (v_cd_wi_workflow_equipment, v_record_equipment.cd_equipment_design, v_record_equipment.nr_ratio, v_record_equipment.ds_notes, now(),v_cd_wi_workflow
              );

      END LOOP;

return v_cd_wi_workflow;
END;
$$
LANGUAGE plpgsql;

ALTER FUNCTION schedule.wi_workflow_duplicate(bigint) SET search_path= schedule,audit, public, translation, docrep;