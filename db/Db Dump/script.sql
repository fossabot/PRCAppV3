mysqldump --routines -u hrms_admin -p  hrms > Dump20171229.sql
mysqldump --routines --triggers -u hrms_admin -p hrms > Dump20171229.sql
mysqldump --routines --triggers --all-databases -u root -p > Dump20180110.sql