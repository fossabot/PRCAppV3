CREATE OR REPLACE FUNCTION tr.importMTEData()
  RETURNS void    AS
$$
DECLARE 
q record;
sample_rec record;
vcd_mte_work_order bigint;
vcd_mte_work_order_sample_data bigint;

BEGIN
    -- Work Order information
    FOR q IN SELECT DISTINCT "ID_WO",
                    "Work_Order_Code",
                    "TRNo",
                    "TestPhase",
                    "Priority",
                    "TR_MET_Project_No",
                    "Customer_Model_No",
                    "Project",
                    "ID_Status",
                    "WOstatus",
                    "Asst_ENG",
                    "Test_ENG",
                    "ID_Purpose",
                    "Purpose","Goal","Unit","startDate","EstCompDate","ActualCompDate",
                    "WO_Timestamp"
               FROM tr."MTE_IMP_TMP_WORK_ORDER_RAW"
    LOOP
        vcd_mte_work_order = NULL;
        SELECT cd_mte_work_order INTO vcd_mte_work_order FROM tr."MTE_WORK_ORDER" where cd_wo_id = q."ID_WO";

        IF NOT FOUND THEN
            SELECT  nextval('tr."MTE_WORK_ORDER_cd_mte_work_order_seq"'::regclass) INTO vcd_mte_work_order;

            INSERT INTO  tr."MTE_WORK_ORDER" values

          (	vcd_mte_work_order,
            q."ID_WO",
            q."Work_Order_Code",
            q."TRNo",
            q."TestPhase",
            q."Priority",
            q."TR_MET_Project_No",
            q."Project",
            q."Customer_Model_No",
            q."ID_Status",
            q."WOstatus",
            q."Asst_ENG",
            q."Test_ENG",
            q."ID_Purpose",
            q."Purpose",q."Goal",q."Unit",q."startDate"::timestamp,q."EstCompDate"::timestamp,q."ActualCompDate"::timestamp,
            q."WO_Timestamp"
           );

        ELSE

            UPDATE tr."MTE_WORK_ORDER"
            SET
            cd_wo_id = q."ID_WO",
            ds_wo_code = q."Work_Order_Code",
            ds_tr_no = q."TRNo",
            ds_test_phase = q."TestPhase",
            ds_priority = q."Priority",
            ds_met_project_no = q."TR_MET_Project_No",
            ds_tti_project_no = q."Project",
            ds_customer_model_no = q."Customer_Model_No",
            nr_status_id = q."ID_Status",
            ds_status = q."WOstatus",
            ds_responsible_worker = q."Asst_ENG",
            ds_technician = q."Test_ENG",
            ds_purpose_id = q."ID_Purpose",
            ds_purpose_code = q."Purpose",nr_wo_goal = q."Goal",ds_unit_code = q."Unit",dt_wo_start_date = q."startDate"::timestamp,
            dt_estimate_completion_date = q."EstCompDate"::timestamp,dt_actual_completion_date = q."ActualCompDate"::timestamp,
            nr_wo_timestamp = q."WO_Timestamp"
            WHERE  cd_mte_work_order = vcd_mte_work_order;
        END IF;

    END LOOP;

    -- work order sample datas
    FOR q IN select "ID_WO","ID_Tool",string_agg("ID_Room",',') "roomIds",string_agg("RoomCode",',') "rooms" from tr."MTE_IMP_TMP_WORK_ORDER_RAW" group by "ID_WO","ID_Tool" order by "ID_WO"

    LOOP
            vcd_mte_work_order_sample_data = NULL;
            sample_rec = null;
            SELECT INTO sample_rec * from tr."MTE_IMP_TMP_WORK_ORDER_RAW" WHERE "ID_WO" = q."ID_WO" and "ID_Tool" = q."ID_Tool" LIMIT 1;
            SELECT cd_mte_work_order_sample_data INTO vcd_mte_work_order_sample_data FROM tr."MTE_WORK_ORDER_SAMPLE_DATA" where cd_wo_id = q."ID_WO" and cd_tool_id = q."ID_Tool";

            IF NOT FOUND THEN
                SELECT  nextval('tr."MTE_WORK_ORDER_SAMPLE_DATA_cd_mte_work_order_sample_data_seq"'::regclass) INTO vcd_mte_work_order_sample_data;
                INSERT INTO tr."MTE_WORK_ORDER_SAMPLE_DATA"
                values (
                       vcd_mte_work_order_sample_data,
                       sample_rec."ID_WO",
                       sample_rec."ID_Tool",
                       sample_rec."Tool",
                       sample_rec."ID_WorkStation",
                       sample_rec."WorkStation",
                       sample_rec."Tool_Status",
                       sample_rec."Comp_Apps",
                       sample_rec."Comp_Discharge",
                       sample_rec."Comp_Cycles",
                       sample_rec."Comp_Runtime",
                       sample_rec."Operator",
                       q."roomIds",
                       q."rooms",
                       sample_rec."Tool_Timestamp"
               );
            ELSE
                UPDATE tr."MTE_WORK_ORDER_SAMPLE_DATA"
                SET
                    cd_wo_id                       = sample_rec."ID_WO",
                    cd_tool_id                     = sample_rec."ID_Tool",
                    ds_tool                        = sample_rec."Tool",
                    nr_workstation_id              = sample_rec."ID_WorkStation"::integer,
                    ds_workstation                 = sample_rec."WorkStation",
                    ds_tool_status                 = sample_rec."Tool_Status",
                    nr_completed_app               = sample_rec."Comp_Apps"::integer,
                    nr_completed_discharge         = sample_rec."Comp_Discharge"::integer,
                    nr_completed_cycle             = sample_rec."Comp_Cycles"::integer,
                    ds_completed_runtime           = sample_rec."Comp_Runtime",
                    ds_operator                    = sample_rec."Operator",
                    ds_room_id                     = q."roomIds",
                    ds_room_code                   = q."rooms",
                    nr_tool_timestamp              = sample_rec."Tool_Timestamp"
                WHERE cd_mte_work_order_sample_data = vcd_mte_work_order_sample_data;
            END IF;
    END LOOP;

END
$$  LANGUAGE plpgsql;
ALTER FUNCTION tr.importMTEData() SET search_path=pg_catalog, public, rfq, tr, tti;
