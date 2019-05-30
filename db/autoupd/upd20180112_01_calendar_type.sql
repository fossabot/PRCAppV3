CREATE TABLE `CALENDAR_TYPE` (
  `cd_calendar_type` int(11) NOT NULL,
  `ds_calendar_type` varchar(64) NOT NULL,
  `fl_holiday` char(1) DEFAULT 'N',
  `fl_special_work_day` char(1) DEFAULT 'N',
  `fl_only_for_user` char(1) DEFAULT 'N',
  `ds_icon` varchar(45) DEFAULT NULL,
  `ds_notes` varchar(128) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_calendar_type`),
  UNIQUE KEY `ds_calendar_type_UNIQUE` (`ds_calendar_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

delimiter // 
CREATE TRIGGER ins_before_CALENDAR_TYPE BEFORE INSERT ON CALENDAR_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_calendar_type IS NULL THEN
        SET NEW.cd_calendar_type = nextval('CALENDAR_TYPE');
     END IF;
END;//
delimiter ;

delimiter // 
CREATE TRIGGER ins_before_EMPLOYEE_BONUS BEFORE INSERT ON EMPLOYEE_BONUS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_bonus IS NULL THEN
        SET NEW.cd_employee_bonus = nextval('EMPLOYEE_BONUS');
     END IF;
END;//
delimiter ;

delimiter // 
CREATE TRIGGER ins_before_EMPLOYEE_TERMINATION BEFORE INSERT ON EMPLOYEE_TERMINATION
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_termination IS NULL THEN
        SET NEW.cd_employee_termination = nextval('EMPLOYEE_TERMINATION');
     END IF;
END;//
delimiter ;

delimiter // 
CREATE TRIGGER ins_before_MOVEMENT_FIRST BEFORE INSERT ON MOVEMENT_FIRST
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_type IS NULL THEN
        SET NEW.cd_benefit_type = nextval('MOVEMENT_FIRST');
     END IF;
END;//
delimiter ;

delimiter // 
CREATE TRIGGER ins_before_MOVEMENT_LAST BEFORE INSERT ON MOVEMENT_LAST
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_type IS NULL THEN
        SET NEW.cd_benefit_type = nextval('MOVEMENT_LAST');
     END IF;
END;//
delimiter ;
