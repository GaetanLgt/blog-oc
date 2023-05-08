<?php
//site name
define('SITE_NAME', 'your-site-name');

//App Root
define('APP_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', '/');
define('URL_SUBFOLDER', '');

//DB Params
define('DB_HOST', $_ENV['DBHOST']);
define('DB_USER', $_ENV['DBUSER']);
define('DB_PASS', $_ENV['DBPASS']);
define('DB_NAME', $_ENV['DBNAME']);