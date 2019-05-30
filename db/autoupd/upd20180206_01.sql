CREATE TABLE `EMPLOYEE_TERMINATION_TYPE` (
  `cd_employee_termination_type` int(11) NOT NULL,
  `ds_employee_termination_type` varchar(64) NOT NULL,
  `fl_involuntary` char(1) NOT NULL DEFAULT 'N',  
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_termination_type`),
  UNIQUE KEY `IUNEMPLOYEE_TERMINATION_TYPE001` (`ds_employee_termination_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

insert into `EMPLOYEE_TERMINATION_TYPE` (`cd_employee_termination_type`, `ds_employee_termination_type`, `fl_involuntary` )
values (1, 'INVOLUNTARY', 'Y');

insert into `EMPLOYEE_TERMINATION_TYPE` (`cd_employee_termination_type`, `ds_employee_termination_type`, `fl_involuntary` )
values (2, 'VOLUNTARY', 'N');



ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
ADD COLUMN `cd_employee_termination_type` INT NULL AFTER `cd_employee_x_bank_branch`;

update EMPLOYEE_TERMINATION set cd_employee_termination_type = 1;

ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
ADD INDEX `FKEMPLOYEE_TERMINATION003_idx` (`cd_employee_termination_type` ASC);
ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
ADD CONSTRAINT `FKEMPLOYEE_TERMINATION003`
  FOREIGN KEY (`cd_employee_termination_type`)
  REFERENCES `hrms`.`EMPLOYEE_TERMINATION_TYPE` (`cd_employee_termination_type`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;


ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
DROP FOREIGN KEY `FKEMPLOYEE_TERMINATION003`;
ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
CHANGE COLUMN `cd_employee_termination_type` `cd_employee_termination_type` INT(11) NOT NULL ;
ALTER TABLE `hrms`.`EMPLOYEE_TERMINATION` 
ADD CONSTRAINT `FKEMPLOYEE_TERMINATION003`
  FOREIGN KEY (`cd_employee_termination_type`)
  REFERENCES `hrms`.`EMPLOYEE_TERMINATION_TYPE` (`cd_employee_termination_type`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;