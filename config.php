<?php
declare(strict_types=1);

require_once dirname(__FILE__) . '/vendor/autoload.php';

define('DOCROOT', dirname(__FILE__) . '/');
define('LENGTH', 8);
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_DATABASE', 'linkbuilder');
define('TABLE', 'redirect');

\ORM::configure('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE);
\ORM::configure('username', MYSQL_USER);
\ORM::configure('password', MYSQL_PASSWORD);