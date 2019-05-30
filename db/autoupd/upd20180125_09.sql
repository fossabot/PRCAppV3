ALTER TABLE `hrms`.`EMPLOYEE` 
ADD COLUMN `cd_employee_manager` INT NULL AFTER `dt_deactivated`,
ADD INDEX `FKEMPLOYEE08_idx` (`cd_employee_manager` ASC);
ALTER TABLE `hrms`.`EMPLOYEE` 
ADD CONSTRAINT `FKEMPLOYEE08`
  FOREIGN KEY (`cd_employee_manager`)
  REFERENCES `hrms`.`EMPLOYEE` (`cd_employee`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;


ALTER TABLE `hrms`.`DOCUMENT` 
CHANGE COLUMN `ds_document` `ds_document` VARCHAR(64) NULL ;


ALTER TABLE `hrms`.`DOCUMENT_TYPE` 
ADD COLUMN `fl_expense` CHAR(1) NULL DEFAULT 'N' AFTER `dt_deactivated`;

