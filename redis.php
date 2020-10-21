<?php

include 'config.php';


$redis = new App\Cache();
$redis->save();

echo 'Кэш-данные сохранены в базу';

?>