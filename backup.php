<?php

include 'config.php';

$redis = new App\cache\Request();
$redis->save();

App\services\Backup::backup();

echo 'Произведен бэкап базы';

?>