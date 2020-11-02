<?php

include 'config.php';


$redis = new App\cache\Request();
$redis->save();

echo 'Кэш-данные сохранены в базу';

?>