ALTER TABLE `hrms`.`FLIGHT_TICKET` 
ADD COLUMN `dt_expiring` DATETIME NULL AFTER `cd_currency`,
ADD COLUMN `dt_used` DATETIME NULL AFTER `dt_expiring`;


INSERT INTO `hrms`.`SYSTEM_PERMISSION` (`ds_system_permission`, `ds_system_permission_id`, `ds_system_parameter_obs`, `cd_type_sys_permission`) VALUES ('SEE EXPIRING INFORMATION ON CALENDAR', 'fl_see_expiring_information_on_calendar', 'Show on Calendar Expiring Information', '1');

ALTER TABLE `hrms`.`DOCUMENT_TYPE` 
ADD COLUMN `fl_show_on_calendar` CHAR(1) NULL DEFAULT 'N' AFTER `fl_expense`;


ALTER TABLE `hrms`.`LOCATION` 
DROP FOREIGN KEY `FKLOCATION01`;
ALTER TABLE `hrms`.`LOCATION` 
DROP COLUMN `cd_country`,
ADD COLUMN `cd_city` INT NULL AFTER `ds_location`,
ADD INDEX `FKLOCATION02_idx` (`cd_city` ASC),
DROP INDEX `FKLOCATION01` ;
ALTER TABLE `hrms`.`LOCATION` 
ADD CONSTRAINT `FKLOCATION02`
  FOREIGN KEY (`cd_city`)
  REFERENCES `hrms`.`CITY` (`cd_city`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
