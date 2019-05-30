
CREATE OR REPLACE FUNCTION rfq.getRfqItemCostDepartment
(
    p_cd_rfq_item bigint
)
RETURNS text AS
$$
DECLARE
v_result text;
BEGIN
    
    SELECT array_to_string(array_agg(     
            COALESCE(( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_COST_CENTER".cd_department_cost_center), 'NA') || '/' || 
            COALESCE(ds_project_number, 'NA') || '/' ||
            COALESCE(ds_project_model_number, 'NA')|| '/' ||
            COALESCE(nr_qtty_to_charge::text, 'NA')
           ), E'\n', null) as ret
      INTO v_result
      FROM "RFQ_COST_CENTER" 
    WHERE cd_rfq_item = p_cd_rfq_item;

return v_result;
END;
$$
LANGUAGE plpgsql STABLE ;

