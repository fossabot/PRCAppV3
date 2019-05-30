CREATE FUNCTION WORKDAYS(first_date DATETIME, second_date DATETIME) 
RETURNS int
BEGIN

    DECLARE start_date DATE;
    DECLARE end_date DATE;
    DECLARE WorkingDays INT;  
    DECLARE dayOfWeek INT;  
    DECLARE var_fl_holiday          char(1);
    DECLARE var_fl_special_work_day char(1);
    

    IF (first_date < second_date) THEN
        SET start_date = date(first_date);
        SET end_date   = date(second_date);
    ELSE
        SET start_date = date(second_date);
        SET end_date   = date(first_date);
    END IF;

    SET WorkingDays = 0;

    

    WHILE end_date >= start_date DO

        SET dayOfWeek = DAYOFWEEK(start_date), 
            var_fl_holiday = 'N', 
            var_fl_special_work_day = 'N';
        
        SELECT COALESCE(max(t.fl_holiday), 'N'), COALESCE(max(t.fl_special_work_day), 'N')
          INTO var_fl_holiday, var_fl_special_work_day
          FROM CALENDAR c, CALENDAR_TYPE t
         WHERE start_date BETWEEN c.dt_start AND c.dt_end
           AND t.cd_calendar_type = c.cd_calendar_type;
        
        /*Fim de semana*/
        IF dayOfWeek = 7 OR dayOfWeek = 1 THEN
            IF var_fl_special_work_day = 'Y' THEN
                SET WorkingDays = WorkingDays + 1;
            END IF;
        ELSE 
            IF var_fl_special_work_day = 'Y' OR var_fl_holiday = 'N' THEN
                SET WorkingDays = WorkingDays + 1;
            END IF;

        END IF;

        SET start_date = date_add(start_date, INTERVAL 1 DAY);

    END WHILE;

    return WorkingDays;

END
