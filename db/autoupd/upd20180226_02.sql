DROP TRIGGER IF EXISTS `hrms`.`ins_before_FLIGHT_TICKET`;

CREATE TRIGGER ins_before_FLIGHT_TICKET BEFORE INSERT ON FLIGHT_TICKET
FOR EACH ROW
BEGIN
	DECLARE cd_movements INT;
    DECLARE cd_benefit_type_new INT;
    DECLARE cd_benefit_operation_new INT;



    IF NEW.cd_flight_ticket IS NULL THEN
        SET NEW.cd_flight_ticket = nextval('FLIGHT_TICKET');
     END IF;


     SELECT cd_benefit_type, cd_benefit_operation
		  INTO cd_benefit_type_new, cd_benefit_operation_new
		FROM FLIGHT_TICKET_TYPE
	  WHERE cd_flight_ticket_type = NEW.cd_flight_ticket_type;


	IF COALESCE(NEW.fl_deduct_from_employee, 'Y') = 'Y'  AND
		cd_benefit_type_new IS NOT NULL AND
        cd_benefit_operation_new IS NOT NULL THEN

		SET cd_movements = nextval('MOVEMENTS');


		INSERT INTO `hrms`.`MOVEMENTS`
		(
        `cd_movements`,
		`cd_employee`,
		`cd_benefit_type`,
		`cd_human_resource_record`,
		`dt_start_at`,
		`nr_value_to_add`,
		`ds_comments`,
		`cd_benefit_operation`
		)  VALUES (cd_movements,
        NEW.cd_employee,
        cd_benefit_type_new,
        getvar('cd_human_resource'),
        date(now()),
        1,
        concat('Added by Flight Ticked #' , convert (NEW.cd_flight_ticket, char(10))),
        cd_benefit_operation_new
        );


		SET NEW.cd_movements = cd_movements;

		CALL adjustMovementBalance(NEW.cd_employee, cd_benefit_type_new);


    END IF;


END;

DROP TRIGGER IF EXISTS `hrms`.`FLIGHT_TICKET_AFTER_UPDATE`;


CREATE TRIGGER `hrms`.`FLIGHT_TICKET_AFTER_UPDATE` AFTER UPDATE ON `FLIGHT_TICKET` FOR EACH ROW
BEGIN

    DECLARE cd_benefit_type_new INT;
    DECLARE cd_benefit_operation_new INT;

    DECLARE cd_benefit_type_old INT;
    DECLARE cd_benefit_operation_old INT;

	 SELECT cd_benefit_type, cd_benefit_operation
		  INTO cd_benefit_type_new, cd_benefit_operation_new
		FROM FLIGHT_TICKET_TYPE
	  WHERE cd_flight_ticket_type = NEW.cd_flight_ticket_type;

	 SELECT cd_benefit_type, cd_benefit_operation
		  INTO cd_benefit_type_old, cd_benefit_operation_old
		FROM FLIGHT_TICKET_TYPE
	  WHERE cd_flight_ticket_type = OLD.cd_flight_ticket_type;

	IF COALESCE(NEW.fl_deduct_from_employee, 'Y') != COALESCE(OLD.fl_deduct_from_employee, 'Y')  OR
		NEW.cd_flight_ticket_type != OLD.cd_flight_ticket_type


    THEN

        -- IF COMPANY GOING TO PAY, DELETE THE MOVEMENT ALSO
		IF ( COALESCE(NEW.fl_deduct_from_employee, 'Y') = 'N'  OR
			   cd_benefit_type_new IS NULL OR
               cd_benefit_operation_new IS NULL ) AND
         NEW.cd_movements IS NOT NULL THEN
    		DELETE FROM `hrms`.`MOVEMENTS` WHERE cd_movements = NEW.cd_movements ;

    	END IF;

		CALL adjustMovementBalance(NEW.cd_employee, cd_benefit_type_new);
		CALL adjustMovementBalance(NEW.cd_employee, cd_benefit_type_old);

	END IF;

END;
