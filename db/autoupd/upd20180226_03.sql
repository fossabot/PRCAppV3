DROP TRIGGER IF EXISTS `hrms`.`ins_before_EMPLOYEE_TERMINATION`;
DROP TRIGGER IF EXISTS `hrms`.`EMPLOYEE_TERMINATION_BEFORE_DELETE`;
DROP TRIGGER IF EXISTS `hrms`.`EMPLOYEE_TERMINATION_BEFORE_UPDATE`;


CREATE TRIGGER ins_before_EMPLOYEE_TERMINATION BEFORE INSERT ON EMPLOYEE_TERMINATION
FOR EACH ROW
BEGIN


    IF NEW.cd_employee_termination IS NULL THEN
        SET NEW.cd_employee_termination = nextval('EMPLOYEE_TERMINATION');
     END IF;


     UPDATE EMPLOYEE
			SET dt_deactivated = NEW.dt_termination
	  WHERE EMPLOYEE.cd_employee = NEW.cd_employee;


END;



CREATE TRIGGER `hrms`.`EMPLOYEE_TERMINATION_BEFORE_UPDATE` BEFORE UPDATE ON `EMPLOYEE_TERMINATION` FOR EACH ROW
BEGIN




    IF NEW.dt_termination != OLD.dt_termination  OR NEW.cd_employee != OLD.cd_employee THEN

     UPDATE EMPLOYEE
			SET dt_deactivated = NULL
	  WHERE EMPLOYEE.cd_employee = OLD.cd_employee;


     UPDATE EMPLOYEE
			SET dt_deactivated = NEW.dt_termination
	  WHERE EMPLOYEE.cd_employee = NEW.cd_employee;


    END IF;


END;



CREATE TRIGGER `hrms`.`EMPLOYEE_TERMINATION_BEFORE_DELETE` BEFORE DELETE ON `EMPLOYEE_TERMINATION` FOR EACH ROW
BEGIN


     UPDATE EMPLOYEE
			SET dt_deactivated = NULL
	  WHERE EMPLOYEE.cd_employee = OLD.cd_employee;

END;