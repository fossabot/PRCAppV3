UPDATE `hrms`.`SYSTEM_SETTINGS_OPTIONS` SET `ds_option_id`='http://172.20.20.20:8080/birt/run?__report=%1&__format=%2&rep_id=%3&rep_auth=%4' WHERE `cd_system_settings_options`='14';
UPDATE `hrms`.`SYSTEM_PARAMETERS` SET `ds_system_parameters_value`='/var/www/hrms/Reports/tmp/' WHERE `cd_system_parameters`='14';



CREATE TABLE `SYSTEM_REPORTS_AUTHORIZATION_PARAM` (
  `cd_system_reports_authorization_param` int(11) NOT NULL AUTO_INCREMENT,
  `cd_system_reports` int(11) NOT NULL,
  `ds_authorization` varchar(64) NOT NULL,
  `ds_key` varchar(64) NOT NULL,
  `ds_value` varchar(128) NOT NULL,
  PRIMARY KEY (`cd_system_reports_authorization_param`),
  KEY `IDXSYSTEM_REPORTS_AUTHORIZATION_PARAM001` (`cd_system_reports`,`ds_authorization`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
