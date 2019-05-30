CREATE TRIGGER `hrms`.`MOVEMENTS_BEFORE_UPDATE` BEFORE UPDATE ON `MOVEMENTS` FOR EACH ROW
BEGIN

    SET NEW.dt_start_at = date(NEW.dt_start_at);

    IF TIMESTAMPDIFF(HOUR, date(NEW.dt_record), NOW())  > 48 AND NEW.dt_start_at != OLD.dt_start_at THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot Change Start On/Apply because it is an old record';
    
    END IF;

	

END