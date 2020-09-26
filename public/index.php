<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Linkbuilder;

define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_DATABASE', 'linkbuilder');

$helloWorld = new \App\Linkbuilder();
$helloWorld->checkIfLink();

?>