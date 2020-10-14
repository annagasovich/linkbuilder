<?php

include 'config.php';


$redis = new App\Cache();
var_dump($redis->check('19d4e7c1'));

?>