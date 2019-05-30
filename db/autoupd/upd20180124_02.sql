DROP TRIGGER IF EXISTS `hrms`.`ins_before_MOVEMENTS`;

DELIMITER $$
CREATE DEFINER=`hrms_admin`@`%` TRIGGER ins_before_MOVEMENTS BEFORE INSERT ON MOVEMENTS
FOR EACH ROW
BEGIN
	DECLARE nr_value_to_add_max decimal(12,2);
	DECLARE nr_value_actual_max   decimal(12,2);
	DECLARE cd_employee_x_bank_branch_max int;
	DECLARE cd_currency_max int;
    DECLARE fl_manual_benef char(1);
	DECLARE v_cd_benefit_operation_new integer;

	declare verror varchar(255);



    IF NEW.cd_movements IS NULL THEN
        SET NEW.cd_movements = nextval('MOVEMENTS');
     END IF;

     
     SET NEW.dt_start_at = date(NEW.dt_start_at);
     
     IF NEW.cd_human_resource_record IS NULL THEN
		SEt NEW.cd_human_resource_record = getvar('cd_human_resource');
     END IF;
          
     SELECT  nr_value_actual, cd_employee_x_bank_branch, cd_currency
			INTO nr_value_actual_max ,cd_employee_x_bank_branch_max, cd_currency_max
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at DESC, cd_movements DESC
             LIMIT 1;
     
    SET nr_value_actual_max = COALESCE(nr_value_actual_max, 0); 
     
	IF COALESCE(NEW.nr_value_actual, 0) > 0 THEN
	   SET NEW.nr_value_to_add = ABS( NEW.nr_value_actual - nr_value_actual_max );

	   IF NEW.nr_value_actual - nr_value_actual_max > 0 THEN
			SET v_cd_benefit_operation_new = 1;
	   ELSE
			SET v_cd_benefit_operation_new = 2;
	   END IF;

      
      
      
		  IF NEW.cd_benefit_operation IS NULL THEN
			   IF NEW.nr_value_actual - nr_value_actual_max > 0 THEN
					SET NEW.cd_benefit_operation = v_cd_benefit_operation_new;
			   END IF;
            
		ELSE 
        
			IF NEW.cd_benefit_operation IS NOT NULL AND NEW.cd_benefit_operation != v_cd_benefit_operation_new THEN
						signal sqlstate '45000' set message_text = 'The Operation (ADD or DEDUCT) is mismatching with the Value Difference';
            END IF;
            END IF;
        
        END IF;

	IF COALESCE(NEW.nr_value_actual, 0) = 0 AND  COALESCE(NEW.nr_value_to_add, 0) > 0  THEN
		IF NEW.cd_benefit_operation IS NULL THEN

		  IF NEW.cd_benefit_operation  = 1 THEN
				SET NEW.nr_value_actual  = nr_value_actual_max + NEW.nr_value_to_add;
		  ELSE
				SET NEW.nr_value_actual  = nr_value_actual_max - NEW.nr_value_to_add;      
		  END IF;
		END IF;

 	END IF;
     
     IF NEW.cd_currency IS NULL AND cd_currency_max IS NOT NULL THEN
		SET NEW.cd_currency = cd_currency_max;
     END IF;
     
     IF NEW.cd_employee_x_bank_branch IS NULL AND cd_employee_x_bank_branch_max IS NOT NULL THEN
		SET NEW.cd_employee_x_bank_branch = cd_employee_x_bank_branch_max;
     END IF;
     
/*	 Set verror = concat('Bla MOV ', convert(  NEW.cd_benefit_operation, char(10)));
    
	SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT =  verror; */

     
     
END$$
DELIMITER ;


