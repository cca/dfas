<?php

define('DEV',false);

if(DEV) {
  // server/db config variables
  define('SITE_URL','http://localhost:8888');
  define('SITE_PATH', '/Users/cwood/Projects/CCA/DFAS/scheduler');
  define('DSN','mysql://root:root@localhost/dfassched');
}
else {
  // server/db config variables
  define('SITE_URL','https://dfas.cca.edu');
  define('SITE_PATH', '/opt/www/dfas');
  define('DSN','mysql://scottdb:scottdb1017@vm-mysql-05.cca.edu/dfassched');
}

// URL of the ETS site, no trailing slash
define('ETS_URL','http://technology.cca.edu');

define('SMARTY_PATH', SITE_PATH.'/');
define('DATA_OBJECT_PATH', SITE_PATH.'/library/DO');

// set the include path
ini_set('include_path','.:'.SITE_PATH.'/library');

// email config variables
define('EMAIL_HOST','mail.cca.edu');
define('EMAIL_PORT',25);
//define('EMAIL_USER','dfas@cca.edu');
//define('EMAIL_PASSWORD','');
define('EMAIL_FROM','dfas@cca.edu');
define('EMAIL_FROM_NAME','CCA DFAS Scheduler (do not reply)');
define('HELP_EMAIL','dfas@cca.edu');

// include the pear libs and smarty lib
require_once 'Smarty/Smarty.class.php';
require_once 'PEAR.php';
require_once 'DB/DataObject.php';
require_once 'Auth.php';
require_once 'Log.php';

// config dataobjects
//define(DB_DATAOBJECT_NO_OVERLOAD,0);
$options = &PEAR::getStaticProperty('DB_DataObject','options');
$options = array(
	'database' => DSN,
	'schema_location' => DATA_OBJECT_PATH,
	'class_location' => DATA_OBJECT_PATH,
	'require_prefix' => 'cca_scheduler/library/DO',
	'class_prefix' => 'DO_'
	);

require_once 'Controller.php';

// include our data object classes
require_once 'DO/Schedules.php';
require_once 'DO/Schedulegroups.php';
require_once 'DO/Reservations.php';
require_once 'DO/Rules.php';
require_once 'DO/Users.php';
require_once 'DO/Usergroups.php';
require_once 'DO/Windows.php';
require_once 'DO/Window_defaults.php';
require_once 'DO/Log_entries.php';

// APP CONFIG CONSTANTS
// how far ahead must a user make a cancellation? (in seconds)	
define('CANCEL_AHEAD_OFFSET',86400); // 24 hours

?>
