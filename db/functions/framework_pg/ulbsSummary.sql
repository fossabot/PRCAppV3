CREATE OR REPLACE FUNCTION public.ulbsSummary
(
    PAR_dt_actualCode bigint
)
    RETURNS void as
$$
DECLARE
vret numeric(10,4);
BEGIN

    -- Crio table with the WO/Samples that we receive from the last import.
    drop table if exists ttMakeULBSWO;
    create temporary table ttMakeULBSWO as
    select distinct nr_work_order  as workorder_number, nr_sample as tool_number  
    FROM "ULBS"
    WHERE "ID" >= PAR_dt_actualCode;

    -- using the WO/Samples we received from before, I grab the last line by fixture (because I need to sum the data when one sample changes the fixture).
    drop table if exists ttMakeULBSWOFULL;
    create temporary table ttMakeULBSWOFULL as
    select max(a."ID") as id, a.fixture_id,  a.nr_work_order as workorder_number, a.nr_sample as tool_number  
    FROM "ULBS" a, ttMakeULBSWO t
    WHERE t.workorder_number   = a.nr_work_order
      AND t.tool_number        = a.nr_sample
    group by a.fixture_id,  a.nr_work_order, a.nr_sample;


    -- Remove from the summarythe WO/Samples that I will recalculate
    DELETE FROM "ULBS_SUMMARY" USING ttMakeULBSWO
     WHERE "ULBS_SUMMARY".nr_workorder_number = ttMakeULBSWO.workorder_number
       AND "ULBS_SUMMARY".nr_tool_number      = ttMakeULBSWO.tool_number;


    -- Insert into ULBS_SUMMARY the grouping data by ID, that is the max by fixture/wo/sample
    INSERT INTO public."ULBS_SUMMARY" (cd_ulbs_summary, 
                                   ds_fixture_id, 
                                   nr_workorder_number, 
                                   nr_tool_number, 
                                   nr_cycle_target, 
                                   nr_cycle_completed, 
                                   it_test_elapse_time, 
                                   ds_test_status, 
                                   ds_logpath_local, 
                                   ds_logpath_remote, 
                                   dt_date_time, 
                                   ds_remark)       

    SELECT max(a."ID"),
           (SELECT fixture_id FROM public."ULBS" x WHERE x."ID" = max(a."ID")),
           w.workorder_number, 
           w.tool_number, 
           sum(a.cycle_target::integer), 
           sum(a.cycle_completed::integer), 
           --sum(a.start_count::integer), 
           --sum(a.stop_count::integer), 
           sum(a.test_elapse_time::interval), 
           (SELECT test_status FROM public."ULBS" x WHERE x."ID" = max(a."ID")), 
           (SELECT logpath_local FROM public."ULBS" x WHERE x."ID" = max(a."ID")),
           (SELECT logpath_remote FROM public."ULBS" x WHERE x."ID" = max(a."ID")),
           max(date_time), 
           (SELECT remark FROM public."ULBS" x WHERE x."ID" = max(a."ID")) 
FROM public."ULBS" a, ttMakeULBSWOFULL w
WHERE a."ID" = w.id
GROUP BY w.workorder_number, w.tool_number;

UPDATE "ULBS_SUMMARY" 
set dt_actual_start = (SELECT min (update_time::timestamp) 
                         FROM "ULBS_EVENTS" x 
                        WHERE x.nr_work_order =  "ULBS_SUMMARY".nr_workorder_number 
                          AND x.nr_sample = "ULBS_SUMMARY".nr_tool_number 
                          AND x.message_type = 'START' 
                          AND COALESCE(trim(x.update_time), '') != '' )
FROM ttMakeULBSWO a
WHERE "ULBS_SUMMARY".nr_workorder_number = a.workorder_number
  AND "ULBS_SUMMARY".nr_tool_number      = a.tool_number;


END;
$$
LANGUAGE plpgsql;


