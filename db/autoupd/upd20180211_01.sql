update sequence_data set sequence_cur_value = 1046 where sequence_name = 'SYSTEM_MENU';

INSERT INTO `hrms`.`SYSTEM_MENU` (`ds_system_menu`, `ds_controller`, `cd_system_menu_parent`, `ds_image`, `fl_always_available`, `fl_visible`, `fl_only_for_super_users`) VALUES ('Organization Chart', 'hrms/employee/companyOrgChart', '1032', '<i class=\"fa fa-external-link-square\"></i>', 'N', 'Y', 'N');
