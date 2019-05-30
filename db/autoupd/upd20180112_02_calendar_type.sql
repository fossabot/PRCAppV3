ALTER TABLE `hrms`.`CALENDAR_TYPE` 
ADD COLUMN `fl_only_for_user` CHAR(1) NULL DEFAULT 'N' AFTER `fl_special_work_day`;



INSERT INTO `hrms`.`CALENDAR_TYPE`
(`ds_calendar_type`,
`fl_holiday`,
`fl_special_work_day`,
`fl_only_for_user`,
`ds_icon`)
VALUES
('HOLIDAY', 'Y', 'N', 'N', 'fa-beer');

INSERT INTO `hrms`.`CALENDAR_TYPE`
(`ds_calendar_type`,
`fl_holiday`,
`fl_special_work_day`,
`fl_only_for_user`,
`ds_icon`)
VALUES
('WORKING HOLIDAY', 'Y', 'N', 'N', 'fa-laptop');

INSERT INTO `hrms`.`CALENDAR_TYPE`
(`ds_calendar_type`,
`fl_holiday`,
`fl_special_work_day`,
`fl_only_for_user`,
`ds_icon`)
VALUES
('CUSTOMER HOLIDAY', 'N', 'N', 'N', 'fa-flag-checkered');

INSERT INTO `hrms`.`CALENDAR_TYPE`
(`ds_calendar_type`,
`fl_holiday`,
`fl_special_work_day`,
`fl_only_for_user`,
`ds_icon`)
VALUES
('BUSINESS TRIP', 'N', 'N', 'Y', 'fa-suitcase');

INSERT INTO `hrms`.`CALENDAR_TYPE`
(`ds_calendar_type`,
`fl_holiday`,
`fl_special_work_day`,
`fl_only_for_user`,
`ds_icon`)
VALUES
('VACATION', 'N', 'N', 'Y', 'fa-fighter-jet');


CREATE TABLE `CALENDAR` (
  `cd_calendar` int(11) NOT NULL,
  `ds_calendar` varchar(64) NOT NULL,
  `dt_start` datetime NOT NULL,
  `dt_end` datetime NOT NULL,
  `cd_calendar_type` int(11) DEFAULT NULL,
  `cd_human_resource_record` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nr_start_year` int(11) GENERATED ALWAYS AS (year(`dt_start`)) STORED,
  `nr_end_year` int(11) GENERATED ALWAYS AS (year(`dt_end`)) STORED,
  PRIMARY KEY (`cd_calendar`),
  KEY `AKCALENDAR001_idx` (`cd_human_resource_record`),
  KEY `FKCALENDAR002_idx` (`cd_calendar_type`),
  KEY `IDXCALENDAR01` (`nr_start_year`),
  KEY `IDXCALENDAR02` (`nr_end_year`),
  CONSTRAINT `FKCALENDAR001` FOREIGN KEY (`cd_human_resource_record`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`),
  CONSTRAINT `FKCALENDAR002` FOREIGN KEY (`cd_calendar_type`) REFERENCES `CALENDAR_TYPE` (`cd_calendar_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TRIGGER IF EXISTS `hrms`.`ins_before_CALENDAR`;

DELIMITER $$
USE `hrms`$$
CREATE DEFINER=`hrms_admin`@`%` TRIGGER ins_before_CALENDAR BEFORE INSERT ON CALENDAR
FOR EACH ROW
BEGIN
    IF NEW.cd_calendar IS NULL THEN
        SET NEW.cd_calendar = nextval('CALENDAR'); 
     END IF;

     SET NEW.cd_human_resource_record = getvar('cd_human_resource');

END$$
DELIMITER ;

ALTER TABLE `hrms`.`LEAVE_TYPE` 
ADD COLUMN `cd_calendar_type` INT NULL AFTER `cd_benefit_operation`,
ADD INDEX `FKLEAVE_TYPE003_idx` (`cd_calendar_type` ASC);
ALTER TABLE `hrms`.`LEAVE_TYPE` 
ADD CONSTRAINT `FKLEAVE_TYPE003`
  FOREIGN KEY (`cd_calendar_type`)
  REFERENCES `hrms`.`CALENDAR_TYPE` (`cd_calendar_type`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;



ALTER TABLE `hrms`.`HR_SYSTEM_DASHBOARD_WIDGET_PARAM` 
CHANGE COLUMN `json_parameters` `json_parameters` VARCHAR(10000) NULL DEFAULT NULL ;
