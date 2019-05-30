
CREATE TABLE `FLIGHT_EXTRA_EXPENSE_TYPE` (
  `cd_flight_extra_expense_type` int(11) NOT NULL,
  `ds_flight_extra_expense_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_flight_extra_expense_type`),
  UNIQUE KEY `IUNFLIGHT_EXTRA_EXPENSE_TYPE001` (`ds_flight_extra_expense_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `hrms`.`SYSTEM_MENU` (`ds_system_menu`, `ds_controller`, `cd_system_menu_parent`, `nr_order`, `ds_image`, `fl_always_available`, `fl_visible`, `fl_only_for_super_users`) VALUES ('Flight Extra Expense', 'hrms/flight_extra_expense_type', '1042', '100', '<i class=\"fa fa-external-link-square\"></i>', 'N', 'Y', 'N');


CREATE TABLE `hrms`.`FLIGHT_TICKET_EXTRA_EXPENSE` (
  `cd_flight_ticket_extra_expense` INT NOT NULL,
  `cd_flight_ticket` INT NOT NULL,
  `cd_flight_extra_expense_type` INT NOT NULL,
  `nr_value_extra_expense` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `dt_record` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_flight_ticket_extra_expense`),
  INDEX `FKFLIGHT_TICKET_EXTRA_EXPENSE02_idx` (`cd_flight_extra_expense_type` ASC),
  INDEX `FKFLIGHT_TICKET_EXTRA_EXPENSE01_idx` (`cd_flight_ticket` ASC),
  CONSTRAINT `FKFLIGHT_TICKET_EXTRA_EXPENSE01`
  FOREIGN KEY (`cd_flight_ticket`)
  REFERENCES `hrms`.`FLIGHT_TICKET` (`cd_flight_ticket`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FKFLIGHT_TICKET_EXTRA_EXPENSE02`
  FOREIGN KEY (`cd_flight_extra_expense_type`)
  REFERENCES `hrms`.`FLIGHT_EXTRA_EXPENSE_TYPE` (`cd_flight_extra_expense_type`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);

ALTER TABLE `hrms`.`FLIGHT_TICKET_EXTRA_EXPENSE`
  ADD COLUMN `ds_comments` VARCHAR(255) NULL AFTER `nr_value_extra_expense`;
