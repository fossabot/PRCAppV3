

drop procedure if exists repMoneyBenefitsToEmployee;

delimiter $$ 

CREATE procedure repMoneyBenefitsToEmployee(PAR_opt int, PAR_period_start char(6), PAR_period_end char(6), PAR_ds_where varchar(10000) )
BEGIN
    /* PAR_opt */
    /* 1. Only Total */
    /* 2. Total By Benefit, adding a line for the total */
    /* 3. Raw information */

    DECLARE done integer default 0;
    DECLARE done2 integer default 0;

    DECLARE cur_cd_employee int;
    DECLARE cur_cd_benefit_type int;
    DECLARE cur_nr_days int;
    DECLARE cur_nr_months int;
    DECLARE cur_dt_start_recurring date;
    DECLARE calc_period char(6);
    DECLARE calc_months_diff int;
    DECLARE calc_mod int;

    DECLARE cur_dt_start date; 
    DECLARE cur_dt_end date;
    DECLARE cur_ds_period char(6); 
    DECLARE cur_ds_start_recurring_period char(6);
    DECLARE cur_nr_first_pay_percent DECIMAL(10,2);
    DECLARE calc_vlr_adjust DECIMAL(10,6);
    DECLARE cur_cd_benefit_operation int;
    DECLARE var_cd_country_china int;
    DECLARE var_tax_deduction_foreigner DECIMAL(10,2);
    DECLARE var_tax_deduction_chinese DECIMAL(10,2);
    DECLARE cur_nr_tax_reduction DECIMAL(10,2);
    DECLARE cur_nr_taxable_value DECIMAL(10,2);
    DECLARE var_cd_benefit_type_salary int;
    DECLARE var_nr_total_tax DECIMAL(10,2);
    DECLARE var_cd_currency_rmb integer;





    SELECT ds_system_parameters_value INTO var_cd_country_china
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'COUNTRY_CHINA';

    SELECT ds_system_parameters_value INTO var_tax_deduction_foreigner
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'TAX_DEDUCTION_FOREIGNER';

    SELECT ds_system_parameters_value INTO var_tax_deduction_chinese
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'TAX_DEDUCTION_CHINESE';

    SELECT ds_system_parameters_value INTO var_tax_deduction_chinese
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'TAX_DEDUCTION_CHINESE';


    SELECT ds_system_parameters_value INTO var_cd_benefit_type_salary
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'BENEFIT_TYPE_SALARY';

    SELECT ds_system_parameters_value INTO var_cd_currency_rmb
      FROM SYSTEM_PARAMETERS where ds_system_parameters_id = 'CURRENCY_RMB_CODE';




    drop table if exists tmpbenefemployee;

    create table tmpbenefemployee (cd_employee int null, 
                                   cd_country int null, 
                                   nr_tax_reduction decimal(12,2)
                                  );


    drop table if exists tmpbenefperiod;
    create temporary table tmpbenefperiod (ds_period                 char(6) null,
                                        dt_start                     date    null,  
                                        dt_end                       date    null  
                                   );


    drop table if exists tmpfakebenef;

    create temporary table tmpfakebenef (cd_benefit_type int, ds_benefit_type varchar(64));

    drop table if exists tmpbenefvalue;

    create temporary table tmpbenefvalue (recid MEDIUMINT NOT NULL AUTO_INCREMENT,
                                        cd_movements               int NULL,
                                        cd_employee                  int NULL,
                                        cd_benefit_type              int NULL,  
                                        ds_period                    char(6) null,
                                        dt_start                     date null,
                                        dt_end                       date null,
                                        cd_currency                  int  null,
                                        cd_employee_x_bank_branch    int  null,
                                        cd_benefit_operation         int  null,
                                        nr_value_actual              DECIMAL(12,2) null,
                                        PRIMARY KEY (recid)
                                   );


    drop table if exists tmpbenefgrp;

    create temporary table tmpbenefgrp (cd_movements               int NULL,
                                        cd_employee                  int NULL,
                                        cd_benefit_type              int NULL,  
                                        ds_period                    char(6) null,
                                        dt_start                     date null,
                                        dt_end                       date null,
                                        cd_currency                  int  null,
                                        cd_employee_x_bank_branch    int  null,
                                        cd_benefit_operation         int  null,
                                        nr_value_actual              DECIMAL(12,2) null
                                   );


    drop table if exists tmpbenef;

    create temporary table tmpbenef (cd_employee                  int  NULL,
                                     cd_benefit_type              int  NULL,  
                                     dt_start_recurring           date NULL,
                                     ds_start_recurring_period    char(6),
                                     cd_benefit_operation         int  null,
                                     nr_months                    int  NULL,
                                     nr_first_pay_percent         DECIMAL(10,2),
                                     cd_country                   int null
                                   );


    SET calc_period = PAR_period_start;

    WHILE calc_period <= PAR_period_end DO
        insert into tmpbenefperiod (ds_period, dt_start, dt_end)
        values (calc_period, date(concat(calc_period, '01')), last_day(date(concat(calc_period, '01'))));
        

        SET calc_period = period_add(calc_period, 1); 
    END WHILE;

    insert into tmpfakebenef (cd_benefit_type, ds_benefit_type)
    values (-50, 'Compensation Termination'),
           (-75, 'Bonus'),
           (-100, 'IIT'),
           (-99, 'IIT Termination'),
           (-98, 'IIT Bonus'),
           (-200, 'Total By Bank/Currency'),
           (-300, 'Total by Currency');

    insert into tmpfakebenef (cd_benefit_type, ds_benefit_type)
    select cd_benefit_type, ds_benefit_type from BENEFIT_TYPE;

    SET @ds_sql = ' INSERT INTO tmpbenef ( cd_employee, 
                                           cd_benefit_type, 
                                           dt_start_recurring,
                                           ds_start_recurring_period,
                                           nr_months,
                                           nr_first_pay_percent,
                                           cd_benefit_operation,
                                           cd_country
                                         )
                         SELECT EMPLOYEE_BENEFIT_TYPE.cd_employee,
                               EMPLOYEE_BENEFIT_TYPE.cd_benefit_type,
                               EMPLOYEE_BENEFIT_TYPE.dt_start_recurring,
                               date_format(EMPLOYEE_BENEFIT_TYPE.dt_start_recurring, "%Y%m"),

                               BENEFIT_FREQUENCY.nr_months,
                               EMPLOYEE_BENEFIT_TYPE.nr_first_pay_percent,
                               EMPLOYEE_BENEFIT_TYPE.cd_benefit_operation,
                               PERSONAL_INFO.cd_country

                          FROM EMPLOYEE_BENEFIT_TYPE
                          JOIN EMPLOYEE          ON (EMPLOYEE.cd_employee         = EMPLOYEE_BENEFIT_TYPE.cd_employee)
                          JOIN BENEFIT_TYPE      ON (BENEFIT_TYPE.cd_benefit_type = EMPLOYEE_BENEFIT_TYPE.cd_benefit_type)
                          JOIN BENEFIT_FREQUENCY ON (BENEFIT_FREQUENCY.cd_benefit_frequency = EMPLOYEE_BENEFIT_TYPE.cd_benefit_frequency)
                          JOIN PERSONAL_INFO          ON (PERSONAL_INFO.cd_personal_info         = EMPLOYEE.cd_personal_info)

                         ' || PAR_ds_where || '  
                           AND EMPLOYEE.dt_deactivated      IS NULL
                           AND EMPLOYEE_BENEFIT_TYPE.dt_deactivated      IS NULL
                           AND EMPLOYEE_BENEFIT_TYPE.cd_benefit_kind = 1
                           AND EMPLOYEE_BENEFIT_TYPE.cd_benefit_unit = 1
                       ' ;

    PREPARE stmt1 FROM @ds_sql; 


    EXECUTE stmt1;
    DEALLOCATE PREPARE stmt1;
    
    INSERT INTO tmpbenefemployee (cd_employee, cd_country, nr_tax_reduction) 
    SELECT distinct cd_employee, cd_country, CASE WHEN cd_country = var_cd_country_china THEN var_tax_deduction_chinese ELSE var_tax_deduction_foreigner END
       FROm tmpbenef;


    -- CURSOR PRA DEFINIR A NECESSIDADE DE DAR MAIS BENEFICIOS!!!
    BEGIN

        DECLARE cur1 CURSOR FOR
        SELECT  cd_employee, 
                cd_benefit_type, 
                dt_start_recurring,
                ds_start_recurring_period,
                nr_months,
                nr_first_pay_percent,
                cd_benefit_operation

          FROM tmpbenef m
        ORDER BY cd_employee ASC, cd_benefit_type ASC;



        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

        OPEN cur1;


        FETCH cur1 INTO cur_cd_employee, 
                        cur_cd_benefit_type, 
                        cur_dt_start_recurring,
                        cur_ds_start_recurring_period,
                        cur_nr_months,
                        cur_nr_first_pay_percent,
                        cur_cd_benefit_operation;
                        
        WHILE done != 1 DO 

            SET done2 = 0;

            BEGIN
                DECLARE cur2 CURSOR FOR
                SELECT  ds_period, 
                        dt_start, 
                        dt_end
                  FROM tmpbenefperiod;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done2 = 1;

                OPEN cur2;


                FETCH cur2 INTO cur_ds_period, 
                                cur_dt_start, 
                                cur_dt_end;

                WHILE done2 != 1 DO 


                    /*Jogo os Recorrentes*/
                    SET calc_months_diff = period_diff(cur_ds_period, cur_ds_start_recurring_period);
                    SET calc_mod = mod(calc_months_diff, cur_nr_months);

                    /* se o mod eh zero, tah na hora de pagar*/
                    IF calc_mod = 0 THEN

                        SET calc_vlr_adjust = 1;

                        IF calc_months_diff = 0 THEN
                            SET calc_vlr_adjust = cur_nr_first_pay_percent / 100;
                        END IF;

                        insert into tmpbenefvalue ( cd_movements,
                                                    cd_employee,
                                                    cd_benefit_type,
                                                    ds_period, 
                                                    dt_start, 
                                                    dt_end,
                                                    cd_currency, 
                                                    cd_employee_x_bank_branch, 
                                                    cd_benefit_operation, 
                                                    nr_value_actual
                                                  )
                        select cd_movements,
                            cd_employee,
                            cd_benefit_type,
                            cur_ds_period, 
                            cur_dt_start, 
                            cur_dt_end,
                            cd_currency, 
                            cd_employee_x_bank_branch, 
                            cur_cd_benefit_operation, 
                            nr_value_actual * calc_vlr_adjust
                          from MOVEMENTS
                         WHERE dt_start_at <= cur_dt_end
                           AND cd_employee     = cur_cd_employee
                           AND cd_benefit_type = cur_cd_benefit_type
                           AND NOT EXISTS ( SELECT 1 
                                              FROM EMPLOYEE_TERMINATION x
                                             WHERE x.cd_employee = MOVEMENTS.cd_employee
                                               AND last_day(x.dt_termination) <= cur_dt_end
                                        )
                       ORDER BY dt_start_at desc, cd_movements desc
                       LIMIT 1;

                    END IF;

                    FETCH cur2 INTO cur_ds_period, 
                                    cur_dt_start, 
                                    cur_dt_end;
                
                END WHILE;



            END;



        FETCH cur1 INTO cur_cd_employee, 
                        cur_cd_benefit_type, 
                        cur_dt_start_recurring,
                        cur_ds_start_recurring_period, 
                        cur_nr_months,
                        cur_nr_first_pay_percent,
                        cur_cd_benefit_operation;

        END WHILE;
    END;

    /*Insiro qualquer valor inserido manualmente para usuarios ativos dentro do periodo. Kind = 3*/
    insert into tmpbenefvalue (     cd_movements,
                                    cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual
                                  )
        select 
            max(m.cd_movements),
            m.cd_employee,
            m.cd_benefit_type,
            min(p.ds_period), 
            min(p.dt_start), 
            min(p.dt_end),
            m.cd_currency, 
            m.cd_employee_x_bank_branch, 
            m.cd_benefit_operation, 
            sum(m.nr_value_actual)
          from MOVEMENTS m, BENEFIT_TYPE t, tmpbenefperiod p, EMPLOYEE e
         WHERE m.cd_employee     = e.cd_employee
           AND m.dt_start_at between p.dt_start and p.dt_end
           AND t.cd_benefit_type = m.cd_benefit_type
           AND t.cd_benefit_kind_default = 3
           AND e.dt_deactivated IS NULL
           AND EXISTS ( SELECT 1 FROM tmpbenef x WHERE x.cd_employee = m.cd_employee)
           AND NOT EXISTS ( SELECT 1 
                             FROM EMPLOYEE_TERMINATION x
                            WHERE x.cd_employee = m.cd_employee
                            AND last_day(x.dt_termination) <= last_day(m.dt_start_at)
                          )
        GROUP BY m.cd_employee, m.cd_benefit_type, m.cd_currency, m.cd_employee_x_bank_branch, m.cd_benefit_operation;




    

        /* CALCULO DO IIT */
        SET done2 = 0;
        BEGIN
        DECLARE cur2 CURSOR FOR
        select t.cd_employee, 
               t.ds_period, 
               sum( CASE WHEN cd_benefit_operation_to_calc_iit = 1 THEN t.nr_value_actual ELSE t.nr_value_actual * -1 END ) as nr_value, 
               e.nr_tax_reduction
          from tmpbenefvalue t, BENEFIT_TYPE b, tmpbenefemployee e
         WHERE b.cd_benefit_type = t.cd_benefit_type
           AND b.cd_benefit_operation_to_calc_iit IS NOT NULL
           AND e.cd_employee = t.cd_employee

           AND t.cd_currency = var_cd_currency_rmb
         group by t.cd_employee, t.ds_period;

        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done2 = 1;

        OPEN cur2;

        FETCH cur2 INTO cur_cd_employee,
                        cur_ds_period, 
                        cur_nr_taxable_value, 
                        cur_nr_tax_reduction;

        WHILE done2 != 1 DO 

            SET cur_nr_taxable_value = cur_nr_taxable_value - cur_nr_tax_reduction;
            SET var_nr_total_tax = 0;
            SELECT round( ( ( cur_nr_taxable_value * nr_tax_rate ) / 100 ), 2) - nr_quick_reduction
             INTO var_nr_total_tax
             FROM IIT_TAX_RATE where  cur_nr_taxable_value between nr_range_start and nr_range_end;

            
        IF COALESCE(var_nr_total_tax, 0) != 0 THEN
            insert into tmpbenefgrp (   cd_employee,
                                        cd_benefit_type,
                                        ds_period, 
                                        dt_start, 
                                        dt_end,
                                        cd_currency, 
                                        cd_benefit_operation, 
                                        nr_value_actual,
                                        cd_employee_x_bank_branch
                                      )

            select  tmpbenefvalue.cd_employee,
                -100 as cd_benefit_type,
                tmpbenefvalue.ds_period, 
                tmpbenefvalue.dt_start, 
                tmpbenefvalue.dt_end,
                tmpbenefvalue.cd_currency, 
                2,
                var_nr_total_tax,
                cd_employee_x_bank_branch

            from tmpbenefvalue

            where cd_employee = cur_cd_employee
              AND ds_period   = cur_ds_period
              AND cd_benefit_type = var_cd_benefit_type_salary;
        END IF;


            FETCH cur2 INTO cur_cd_employee,
                            cur_ds_period, 
                            cur_nr_taxable_value, 
                            cur_nr_tax_reduction;
        END WHILE;

        insert into tmpbenefvalue (cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual)
        select cd_employee,
               cd_benefit_type,
               ds_period, 
               dt_start, 
               dt_end,
               cd_currency, 
               cd_employee_x_bank_branch, 
               cd_benefit_operation, 
               nr_value_actual
        from tmpbenefgrp;

        delete from tmpbenefgrp;

    END;
    /* CALCULO DO IIT - FIM */

   

    /* CALCULO DOS BONUS */
    SET done2 = 0;
    BEGIN
        DECLARE cur2 CURSOR FOR
        select distinct e.cd_employee, 
               p.ds_period, 
               p.dt_start,
               p.dt_end
          from tmpbenefemployee e, tmpbenefperiod p;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done2 = 1;

        OPEN cur2;

        FETCH cur2 INTO cur_cd_employee,
                        cur_ds_period, 
                        cur_dt_start, 
                        cur_dt_end;

        WHILE done2 != 1 DO 


            insert into tmpbenefgrp (   cd_employee,
                                        cd_benefit_type,
                                        ds_period, 
                                        dt_start, 
                                        dt_end,
                                        cd_currency, 
                                        cd_benefit_operation, 
                                        nr_value_actual,
                                        cd_employee_x_bank_branch
                                      )

            select cur_cd_employee,
                   -75,
                   cur_ds_period, 
                   cur_dt_start, 
                   cur_dt_end, 
                   c.cd_currency,
                   1, 
                   c.nr_final_pay,
                   c.cd_employee_x_bank_branch
            FROM EMPLOYEE_BONUS c
           WHERE c.cd_employee = cur_cd_employee
             AND c.dt_apply between cur_dt_start AND cur_dt_end;

            /* IIT */

            insert into tmpbenefgrp (   cd_employee,
                                        cd_benefit_type,
                                        ds_period, 
                                        dt_start, 
                                        dt_end,
                                        cd_currency, 
                                        cd_benefit_operation, 
                                        nr_value_actual,
                                        cd_employee_x_bank_branch
                                      )

            select cur_cd_employee,
                   -98,
                   cur_ds_period, 
                   cur_dt_start, 
                   cur_dt_end, 
                   c.cd_currency,
                   2, 
                   c.nr_iit,
                   c.cd_employee_x_bank_branch
            FROM EMPLOYEE_BONUS c
           WHERE c.cd_employee = cur_cd_employee
             AND c.nr_iit > 0
             AND c.dt_apply between cur_dt_start AND cur_dt_end;


            FETCH cur2 INTO cur_cd_employee,
                            cur_ds_period, 
                            cur_dt_start, 
                            cur_dt_end;
        END WHILE;


        insert into tmpbenefvalue (cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual)
        select cd_employee,
               cd_benefit_type,
               ds_period, 
               dt_start, 
               dt_end,
               cd_currency, 
               cd_employee_x_bank_branch, 
               cd_benefit_operation, 
               nr_value_actual
        from tmpbenefgrp;

        delete from tmpbenefgrp;

    END;


    /* FIM DOS BONUS */

    /* CALCULO DOS TERMINATION */
    SET done2 = 0;
    BEGIN
        DECLARE cur2 CURSOR FOR
        select distinct e.cd_employee, 
               p.ds_period, 
               p.dt_start,
               p.dt_end
          from tmpbenefemployee e, tmpbenefperiod p;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done2 = 1;

        OPEN cur2;

        FETCH cur2 INTO cur_cd_employee,
                        cur_ds_period, 
                        cur_dt_start, 
                        cur_dt_end;

        WHILE done2 != 1 DO 


            insert into tmpbenefgrp (   cd_employee,
                                        cd_benefit_type,
                                        ds_period, 
                                        dt_start, 
                                        dt_end,
                                        cd_currency, 
                                        cd_benefit_operation, 
                                        nr_value_actual,
                                        cd_employee_x_bank_branch
                                      )

            select cur_cd_employee,
                   -50,
                   cur_ds_period, 
                   cur_dt_start, 
                   cur_dt_end, 
                   c.cd_currency,
                   1, 
                   c.nr_total_compensation_fee,
                   c.cd_employee_x_bank_branch
            FROM EMPLOYEE_TERMINATION c
           WHERE c.cd_employee = cur_cd_employee
             AND c.dt_termination between cur_dt_start AND cur_dt_end;

            /*IIT*/
            insert into tmpbenefgrp (   cd_employee,
                                        cd_benefit_type,
                                        ds_period, 
                                        dt_start, 
                                        dt_end,
                                        cd_currency, 
                                        cd_benefit_operation, 
                                        nr_value_actual,
                                        cd_employee_x_bank_branch
                                      )

            select cur_cd_employee,
                   -99,
                   cur_ds_period, 
                   cur_dt_start, 
                   cur_dt_end, 
                   c.cd_currency,
                   2, 
                   c.nr_iit,
                   c.cd_employee_x_bank_branch
            FROM EMPLOYEE_TERMINATION c
           WHERE c.cd_employee = cur_cd_employee
             AND c.dt_termination between cur_dt_start AND cur_dt_end;

            FETCH cur2 INTO cur_cd_employee,
                            cur_ds_period, 
                            cur_dt_start, 
                            cur_dt_end;
        END WHILE;


        insert into tmpbenefvalue (cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual)
        select cd_employee,
               cd_benefit_type,
               ds_period, 
               dt_start, 
               dt_end,
               cd_currency, 
               cd_employee_x_bank_branch, 
               cd_benefit_operation, 
               nr_value_actual
        from tmpbenefgrp;

        delete from tmpbenefgrp;

    END;



     /* ************************ RETORNO ********************************/
    /*Total by employee/Benefit/Bank*/
    IF PAR_opt = 1 THEN

        select  min(recid) as recid ,
            tmpbenefvalue.cd_employee,
            min( ( select ds_employee from EMPLOYEE WHERE cd_employee = tmpbenefvalue.cd_employee )) as ds_employee,

            tmpbenefvalue.ds_period, 
            tmpbenefvalue.cd_currency, 
            min( ( select ds_currency from CURRENCY WHERE cd_currency = tmpbenefvalue.cd_currency )) as ds_currency,

            tmpbenefvalue.cd_employee_x_bank_branch, 
            min( (( select ds_bank FROM BANK WHERE cd_bank =  BANK_BRANCH.cd_bank) || '/' || BANK_BRANCH.ds_bank_branch  || '/' || EMPLOYEE_X_BANK_BRANCH.ds_account_number )) as ds_employee_x_bank_branch,

            sum( CASE WHEN tmpbenefvalue.cd_benefit_operation = 1 THEN tmpbenefvalue.nr_value_actual ELSE tmpbenefvalue.nr_value_actual * -1 END ) as nr_value_actual,

            min(tmpbenefvalue.dt_start) as dt_start, 
            min(tmpbenefvalue.dt_end) as dt_end,
            min( monthname(tmpbenefvalue.dt_end)) as ds_month,
            min( year(tmpbenefvalue.dt_end)) as ds_year,
            min( date_format(tmpbenefvalue.dt_end, "%m/%Y")) as ds_period_readable




        from tmpbenefvalue
        LEFT OUTER JOIN EMPLOYEE_X_BANK_BRANCH ON (EMPLOYEE_X_BANK_BRANCH.cd_employee_x_bank_branch = tmpbenefvalue.cd_employee_x_bank_branch)
        LEFT OUTER JOIN BANK_BRANCH ON (BANK_BRANCH.cd_bank_branch = EMPLOYEE_X_BANK_BRANCH.cd_bank_branch)
        
        GROUP BY tmpbenefvalue.ds_period, 
                 tmpbenefvalue.cd_employee,
                 tmpbenefvalue.cd_employee_x_bank_branch,
                 tmpbenefvalue.cd_currency
        ORDER BY ds_employee, ds_period;

    END IF;


   

    /*Raw data com subtotal. Adiciono informacao extra mas o select fica por conta do 3.*/
    IF PAR_opt = 1 OR PAR_opt = 2 THEN
        
        insert into tmpbenefgrp (   cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual
                                  )

        select  tmpbenefvalue.cd_employee,
            -200 as cd_benefit_type,
            tmpbenefvalue.ds_period, 
            min(tmpbenefvalue.dt_start) as dt_start, 
            min(tmpbenefvalue.dt_end) as dt_end,
            tmpbenefvalue.cd_currency, 
            tmpbenefvalue.cd_employee_x_bank_branch, 
            min(tmpbenefvalue.cd_benefit_operation) as cd_benefit_operation,
            sum( CASE WHEN tmpbenefvalue.cd_benefit_operation = 1 THEN tmpbenefvalue.nr_value_actual ELSE tmpbenefvalue.nr_value_actual * -1 END ) as nr_value_actual

        from tmpbenefvalue
        
        GROUP BY tmpbenefvalue.ds_period, 
                 tmpbenefvalue.cd_employee,
                 tmpbenefvalue.cd_employee_x_bank_branch,
                 tmpbenefvalue.cd_currency;
        

        insert into tmpbenefgrp (   cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_benefit_operation, 
                                    nr_value_actual
                                  )

        select  tmpbenefvalue.cd_employee,
            -300 as cd_benefit_type,
            tmpbenefvalue.ds_period, 
            min(tmpbenefvalue.dt_start) as dt_start, 
            min(tmpbenefvalue.dt_end) as dt_end,
            tmpbenefvalue.cd_currency, 
            min(tmpbenefvalue.cd_benefit_operation) as cd_benefit_operation,
            sum( CASE WHEN tmpbenefvalue.cd_benefit_operation = 1 THEN tmpbenefvalue.nr_value_actual ELSE tmpbenefvalue.nr_value_actual * -1 END ) as nr_value_actual

        from tmpbenefvalue
        
        GROUP BY tmpbenefvalue.ds_period, 
                 tmpbenefvalue.cd_employee,
                 tmpbenefvalue.cd_currency;



       
        insert into tmpbenefvalue (cd_employee,
                                    cd_benefit_type,
                                    ds_period, 
                                    dt_start, 
                                    dt_end,
                                    cd_currency, 
                                    cd_employee_x_bank_branch, 
                                    cd_benefit_operation, 
                                    nr_value_actual)
        select cd_employee,
               cd_benefit_type,
               ds_period, 
               dt_start, 
               dt_end,
               cd_currency, 
               cd_employee_x_bank_branch, 
               cd_benefit_operation, 
               nr_value_actual
        from tmpbenefgrp;


    END IF;


    /*retorno apenas os totais*/
    IF PAR_opt = 1 THEN
        select recid,
            tmpbenefvalue.cd_employee,
            ( select ds_employee from EMPLOYEE WHERE cd_employee = tmpbenefvalue.cd_employee ) as ds_employee,
            tmpbenefvalue.cd_benefit_type,
            ( SELECT ds_benefit_type 
                             FROM tmpfakebenef 
                            WHERE cd_benefit_type = tmpbenefvalue.cd_benefit_type 
            ) as ds_benefit_type,

            tmpbenefvalue.ds_period, 
            tmpbenefvalue.dt_start, 
            tmpbenefvalue.dt_end,
            tmpbenefvalue.cd_currency, 
            ( select ds_currency from CURRENCY WHERE cd_currency = tmpbenefvalue.cd_currency ) as ds_currency,

            tmpbenefvalue.cd_employee_x_bank_branch, 
            ( ( select ds_bank FROM BANK WHERE cd_bank =  BANK_BRANCH.cd_bank) || '/' || BANK_BRANCH.ds_bank_branch  || '/' || EMPLOYEE_X_BANK_BRANCH.ds_account_number ) as ds_employee_x_bank_branch,

            tmpbenefvalue.cd_benefit_operation, 
            nr_value_actual,
            monthname(tmpbenefvalue.dt_end) as ds_month,
            year(tmpbenefvalue.dt_end) as ds_year,
            date_format(tmpbenefvalue.dt_end, "%m/%Y") as ds_period_readable
        from tmpbenefvalue
        LEFT OUTER JOIN EMPLOYEE_X_BANK_BRANCH ON (EMPLOYEE_X_BANK_BRANCH.cd_employee_x_bank_branch = tmpbenefvalue.cd_employee_x_bank_branch)
        LEFT OUTER JOIN BANK_BRANCH ON (BANK_BRANCH.cd_bank_branch = EMPLOYEE_X_BANK_BRANCH.cd_bank_branch)
        WHERE cd_benefit_type = -100
        ORDER BY ds_period, ds_employee, ( CASE WHEN tmpbenefvalue.cd_benefit_type > 0 THEN 1 ELSE tmpbenefvalue.cd_benefit_type END) DESC,  tmpbenefvalue.cd_benefit_operation, nr_value_actual desc, ds_benefit_type ;


    END IF;

    /*Raw data ou retorno do 2*/
    IF PAR_opt = 2 OR PAR_opt = 3 THEN

        select recid,
            tmpbenefvalue.cd_employee,
            ( select ds_employee from EMPLOYEE WHERE cd_employee = tmpbenefvalue.cd_employee ) as ds_employee,
            tmpbenefvalue.cd_benefit_type,
            ( SELECT ds_benefit_type 
                             FROM tmpfakebenef 
                            WHERE cd_benefit_type = tmpbenefvalue.cd_benefit_type 
            ) as ds_benefit_type,
            tmpbenefvalue.ds_period, 
            tmpbenefvalue.dt_start, 
            tmpbenefvalue.dt_end,
            tmpbenefvalue.cd_currency, 
            ( select ds_currency from CURRENCY WHERE cd_currency = tmpbenefvalue.cd_currency ) as ds_currency,

            tmpbenefvalue.cd_employee_x_bank_branch, 
            ( ( select ds_bank FROM BANK WHERE cd_bank =  BANK_BRANCH.cd_bank) || '/' || BANK_BRANCH.ds_bank_branch  || '/' || EMPLOYEE_X_BANK_BRANCH.ds_account_number ) as ds_employee_x_bank_branch,

            tmpbenefvalue.cd_benefit_operation, 
            nr_value_actual,
            monthname(tmpbenefvalue.dt_end) as ds_month,
            year(tmpbenefvalue.dt_end) as ds_year,
            date_format(tmpbenefvalue.dt_end, "%m/%Y") as ds_period_readable,
            (CASE WHEN cd_benefit_type = -200 then 'background-color: lightcyan'
                 WHEN cd_benefit_type = -300 then 'background-color: lightgrey'
                 ELSE ''
            END) as style
        from tmpbenefvalue
        LEFT OUTER JOIN EMPLOYEE_X_BANK_BRANCH ON (EMPLOYEE_X_BANK_BRANCH.cd_employee_x_bank_branch = tmpbenefvalue.cd_employee_x_bank_branch)
        LEFT OUTER JOIN BANK_BRANCH ON (BANK_BRANCH.cd_bank_branch = EMPLOYEE_X_BANK_BRANCH.cd_bank_branch)
        ORDER BY ds_period, ds_employee, ( CASE WHEN tmpbenefvalue.cd_benefit_type > 0 THEN 1 ELSE tmpbenefvalue.cd_benefit_type END) DESC,  tmpbenefvalue.cd_benefit_operation, nr_value_actual desc, ds_benefit_type ;

    END IF;


    

END $$ 
delimiter ; 