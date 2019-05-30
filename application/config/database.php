<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
//$active_record = TRUE;
$query_builder = true;
//echo ('vai '. $_SERVER('DB'));
//return ;

//error_reporting(E_STRICT);
//error_reporting(E_NOTICE);
IF ($_SERVER['pgsqlserver'] == 'NONE') {
    $_SERVER['pgsqlserver']= '';
}

$db['default']['hostname'] = $_SERVER['pgsqlserver'];
$db['default']['username'] = $_SERVER['pgsqluser'];
$db['default']['password'] = $_SERVER['pgsqlpass'];
$db['default']['database'] = $_SERVER['pgsqldb'] ; //'mboard';
$db['default']['dbdriver'] = 'postgre';
$db['default']['port']     = $_SERVER['pgsqlport'] ;
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
$db['default']['schema'] ='audit, docrep,reports, translation,tti, tr, schedule, rfq, training, assets, inventory';


$db['mte']['hostname'] = '10.64.20.134';
$db['mte']['username'] = 'Userquery';
$db['mte']['password'] = 'query123';
$db['mte']['database'] = 'WIDisplaySystem';
$db['mte']['dbdriver'] = 'sqlsrv';
$db['mte']['port']     = '1433' ;
$db['mte']['dbprefix'] = '';
$db['mte']['pconnect'] = FALSE;
$db['mte']['db_debug'] = TRUE;
$db['mte']['cache_on'] = FALSE;
$db['mte']['cachedir'] = '';
$db['mte']['char_set'] = 'utf8';
$db['mte']['dbcollat'] = 'utf8_general_ci';
$db['mte']['swap_pre'] = '';
$db['mte']['autoinit'] = FALSE;
$db['mte']['stricton'] = FALSE;
//$db['mte']['schema'] ='audit, docrep,reports, translation,tti, tr, schedule';



$db['tr']['hostname'] = '10.64.16.129';
$db['tr']['username'] = 'qasystems';
$db['tr']['password'] = 'q@syst&ms';
$db['tr']['database'] = 'UseTest2';
$db['tr']['dbdriver'] = 'sqlsrv';
$db['tr']['port']     = '1433' ;
$db['tr']['dbprefix'] = '';
$db['tr']['pconnect'] = FALSE;
$db['tr']['db_debug'] = TRUE;
$db['tr']['cache_on'] = FALSE;
$db['tr']['cachedir'] = '';
$db['tr']['char_set'] = 'utf8';
$db['tr']['dbcollat'] = 'utf8_general_ci';
$db['tr']['swap_pre'] = '';
$db['tr']['autoinit'] = FALSE;
$db['tr']['stricton'] = FALSE;

$db['trone']['hostname'] = '10.64.16.129';
$db['trone']['username'] = 'qasystems';
$db['trone']['password'] = 'q@syst&ms';
$db['trone']['database'] = 'UseTest';
$db['trone']['dbdriver'] = 'sqlsrv';
$db['trone']['port']     = '1433' ;
$db['trone']['dbprefix'] = '';
$db['trone']['pconnect'] = FALSE;
$db['trone']['db_debug'] = TRUE;
$db['trone']['cache_on'] = FALSE;
$db['trone']['cachedir'] = '';
$db['trone']['char_set'] = 'utf8';
$db['trone']['dbcollat'] = 'utf8_general_ci';
$db['trone']['swap_pre'] = '';
$db['trone']['autoinit'] = FALSE;
$db['trone']['stricton'] = FALSE;


$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = tcp)(HOST = cnskropd1.cn.globaltti.net)(PORT = 1575))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = PDFCARC1)))';

$db['onekey']['hostname'] = $tnsname;
$db['onekey']['username'] = 'TTILABQ1';
$db['onekey']['password'] = 'qryone65621key';
$db['onekey']['database'] = '';
$db['onekey']['dbdriver'] = 'oci8';
$db['onekey']['dbprefix'] = '';
$db['onekey']['pconnect'] = TRUE;
$db['onekey']['db_debug'] = TRUE;
$db['onekey']['cache_on'] = FALSE;
$db['onekey']['cachedir'] = '';
$db['onekey']['char_set'] = 'utf8';
$db['onekey']['dbcollat'] = 'utf8_general_ci';
$db['onekey']['swap_pre'] = '';
$db['onekey']['autoinit'] = FALSE;
$db['onekey']['stricton'] = FALSE;

$db['faceid']['hostname'] = '10.64.20.131';
$db['faceid']['username'] = 'hanvon';
$db['faceid']['password'] = 'hanvon';
$db['faceid']['database'] = 'HWATT';
$db['faceid']['dbdriver'] = 'sqlsrv';
$db['faceid']['port']     = '1433' ;
$db['faceid']['dbprefix'] = '';
$db['faceid']['pconnect'] = FALSE;
$db['faceid']['db_debug'] = TRUE;
$db['faceid']['cache_on'] = FALSE;
$db['faceid']['cachedir'] = '';
$db['faceid']['char_set'] = 'utf8';
$db['faceid']['dbcollat'] = 'utf8_general_ci';
$db['faceid']['swap_pre'] = '';
$db['faceid']['autoinit'] = FALSE;
$db['faceid']['stricton'] = FALSE;


$db['ulbs']['hostname'] = '10.64.125.63';
$db['ulbs']['username'] = 'REL-Viewer';
$db['ulbs']['password'] = 'batata';
$db['ulbs']['database'] = 'milwaukeerellab';
$db['ulbs']['dbdriver'] = 'mysqli';
$db['ulbs']['port']     = '3310' ;
$db['ulbs']['dbprefix'] = '';
$db['ulbs']['pconnect'] = FALSE;
$db['ulbs']['db_debug'] = TRUE;
$db['ulbs']['cache_on'] = FALSE;
$db['ulbs']['cachedir'] = '';
$db['ulbs']['char_set'] = 'utf8';
$db['ulbs']['dbcollat'] = 'utf8_general_ci';
$db['ulbs']['swap_pre'] = '';
$db['ulbs']['autoinit'] = FALSE;
$db['ulbs']['stricton'] = FALSE;




/* End of file database.php */
/* Location: ./application/config/database.php */
