CREATE OR REPLACE FUNCTION public.imppurchasepast()
  RETURNS void    AS
$$
DECLARE 
r record;

vsupplier   integer;
vcd_equipment_design integer;
v_ds_equiment_code text;
v_ds_temp          text;
vfladd        char(1);
vcat   integer;
vsubcat   integer;
vunit     integer;
vgrade     integer;



BEGIN

    delete from imppurchasepast_log;

    FOR r IN SELECT *
               FROM impsuppast 
         where COALESCE( nr_unit_price, 0) > 0 
          AND substring (ds_equipment_code FROM 1 FOR 1) in ('F', 'C') and position(  '&' in ds_equipment_code) = 0
        ORDER BY dt_date DESC
     LOOP

     
        vsupplier = NULL;
        vcd_equipment_design = NULL;
        

        IF r.ds_vendor_code != 'NA' THEN
            SELECT cd_supplier INTO vsupplier FROM "SUPPLIER" where ds_vendor_code = r.ds_vendor_code;

        ELSE
            SELECT cd_supplier INTO vsupplier FROM "SUPPLIER" where ds_supplier = r.ds_supplier_description;
            IF NOT FOUND THEN
                vsupplier = nextval('rfq."SUPPLIER_cd_supplier_seq"'::regclass);
                INSERT INTO rfq."SUPPLIER" (cd_supplier, ds_supplier, ds_supplier_alt, fl_tti_supplier) values (vsupplier, r.ds_supplier_description, r.ds_supplier_description, 'N');
            END IF;
        END IF;

       v_ds_equiment_code = split_part(r.ds_equipment_code,  '-', 1) || '-' || rtrim(ltrim(to_char(split_part(r.ds_equipment_code,'-', 2)::integer, '000')));

       SELECT cd_equipment_design INTO vcd_equipment_design FROM rfq."EQUIPMENT_DESIGN" where rfq.retEquipmentCode(cd_equipment_design) = v_ds_equiment_code;

       if vcd_equipment_design IS NULL OR vsupplier IS NULL THEN

       v_ds_temp = r.ds_vendor_code || ' - ' || r.ds_supplier_description; 

        INSERT INTO rfq.imppurchasepast_log
            (ds_supplier_xls, ds_equipment_design_code, cd_supplier_system, cd_equipment_design_system)
        VALUES
            (v_ds_temp, r.ds_equipment_code, vsupplier, vcd_equipment_design);

       ELSE 
            INSERT INTO rfq."RFQ_PAST_PURCHASES" (cd_supplier, cd_equipment_design, 
                                                  ds_department_code, 
                                                  ds_type_and_project, 
                                                  nr_total_quantity, 
                                                  nr_unit_price,  
                                                  nr_total_price, 
                                                  ds_requested_by, 
                                                  ds_pr_por, 
                                                  ds_workflow_number, 
                                                  ds_po_number, 
                                                  ds_equipment_design_description,
                                                  ds_equipment_design_full_code,
                                                  dt_requested)
            VALUES (vsupplier, vcd_equipment_design, 
                    r.ds_department, 
                    r.ds_type_and_project, 
                    r.nr_qty, 
                    round(r.nr_unit_price_without_vat, 2),  
                    round(r.nr_qty * r.nr_unit_price_without_vat ,2) , 
                    r.ds_requested_by, 
                    r.ds_pr_por, 
                    r.ds_workflow_number, 
                    r.ds_po_number, 
                    r.ds_description,
                    r.ds_equipment_code,
                    r.dt_date
                  );


       END IF;
        
    END LOOP;




END
$$  LANGUAGE plpgsql;

ALTER FUNCTION public.imppurchasepast() SET search_path=pg_catalog, public, rfq;