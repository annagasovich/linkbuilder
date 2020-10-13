<?php

include 'config.php';


$redis = new App\Cache();
$redis->rebuild();

?>