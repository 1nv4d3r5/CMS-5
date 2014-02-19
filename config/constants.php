<?php
	define('SITE_ROOT', dirname(dirname(__FILE__)));
	define ('HOST', '');
	define ('USER', '');
	define ('PASS', '');
	define ('DBNAME', '');
	define('PDO_DSN', 'mysql:host=' . HOST . ';dbname=' . DBNAME);
	define('DB_PERSISTENCY', true);
	define ('SITE_TITLE', '');

//ftp credentials for dir creation and file writing	
	define ('FTP_USER','');
	define ('FTP_PASS','');


	define('EVENT_PER_PAGE', 3);
	define('NEWS_PER_PAGE', 9);
	define('ANNOUNCEMENT_PER_PAGE', 10);
	define('TENDER_PER_PAGE', 10);


	define('cmsEVENT_PER_PAGE', 10);
	define('cmsNEWS_PER_PAGE', 10);
	define('cmsANNOUNCEMENT_PER_PAGE', 10);
	define('cmsTENDER_PER_PAGE', 10);

	define('HTTP_SERVER_PORT', '80');
	define('VIRTUAL_LOCATION', '/cms/');

	//Error handling parametres
	define('IS_WARNING_FATAL', true);
	define('DEBUGGING', true);
	define('ERROR_TYPES', E_ALL);

	//Mailing system parametres
	define('SEND_ERROR_MAIL', false);
	define('ADMIN_ERROR_MAIL', '');
	define('SEND_FROM', '');

	//Error logging parametres
	define('LOG_ERRORS', true);
	define('LOG_ERROR_FILE', '');
	define('SITE_GENERIC_ERROR_MESSAGE', '<h1> Site: An Error Occured </h1>');

	//Subscription System Mailer
	define('MAIL_HOST', '');
	define('MAIL_PORT', 465);
	define('MAIL_FROM', '');
	define('MAIL_FROM_PWD', '');
	define('FROM_ADDRESS', '');

?>