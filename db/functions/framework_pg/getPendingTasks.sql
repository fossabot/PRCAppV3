
CREATE OR REPLACE FUNCTION public.getPendingTasks(PAR_cd_hm_system_dashboard_widget_param integer)
  RETURNS TABLE (
  recid bigint,
  nr_type integer,
  ds_type text,
  ds_pending_action text, 
  dt_requested text,
  dt_requested_original timestamptz,
  ds_action text,
  ds_notes_on_action text,
  nr_key bigint,
  ds_from text
)   AS
$$
DECLARE 
   vmessage_rfq text;
   v_record record;
   vrecid bigint;
   v_cd_human_resource integer;

BEGIN

    SELECT get_var('cd_human_resource') INTO v_cd_human_resource;

    -- rfq - Key: 1
    vmessage_rfq = retDescTranslatedNew('%s for %s (%s)', null);
    vrecid = 1;

    FOR v_record IN 
        SELECT COALESCE ( (SELECT ds_approval_steps_config from "APPROVAL_STEPS_CONFIG" WHERE "APPROVAL_STEPS_CONFIG".cd_approval_steps_config =  "RFQ_APPROVAL_STEPS".cd_approval_steps_config ), 'FINISHED')  as ds_approval_steps_config_pending,
               "RFQ".cd_rfq,
               "RFQ_APPROVAL_STEPS".dt_record,
               ( SELECT ds_human_resource_full FROM "HUMAN_RESOURCE" where "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant) as ds_from,
               ( SELECT min(d.ds_department) FROM "HUMAN_RESOURCE" a, "JOBS_HUMAN_RESOURCE" b, "JOBS" c, "DEPARTMENT" d where a.cd_human_resource = "RFQ".cd_human_resource_applicant AND b.cd_human_resource = a.cd_human_resource AND c.cd_jobs = b.cd_jobs AND d.cd_department = c.cd_department) as ds_department


          FROM "RFQ"
         JOIN "RFQ_APPROVAL_STEPS" ON ("RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL)
        WHERE "RFQ".dt_deactivated IS NULL
          AND EXISTS ( SELECT 1 FROM "APPROVAL_STEPS_CONFIG" where "RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL  AND "APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config AND getUserPermission(ds_system_permission_ids, v_cd_human_resource  ) = 'Y')
          AND EXISTS ( SELECT 1 
                        FROM getUsersByPermissionForSelect("RFQ".cd_human_resource_applicant, 'fl_rfq_create_and_update;fl_rfq_purchase_department;fl_rfq_quotation_release;fl_rfq_release_pr;fl_rfq_release_to_quote;fl_rfq_team_approval;fl_rfq_department_manager' ) WHERE cd_human_resource = v_cd_human_resource
                      )
    LOOP
        
        recid             = vrecid;
        nr_type           = 1;
        ds_type           = 'PUR';
        ds_pending_action = format(vmessage_rfq, v_record.ds_approval_steps_config_pending, v_record.ds_from, v_record.ds_department);
        dt_requested          = to_char(v_record.dt_record, 'mm/dd/yyyy HH24:MI');
        dt_requested_original = v_record.dt_record;
        ds_action         = NULL;
        ds_notes_on_action = NULL;
        nr_key             = v_record.cd_rfq;
        ds_from             =v_record.ds_from;

        vrecid =  vrecid  + 1;

        RETURN NEXT;


    END LOOP;

END
$$  LANGUAGE plpgsql;
ALTER FUNCTION public.getPendingTasks(integer) SET search_path=audit, public, translation, docrep, rfq;