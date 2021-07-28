<?php

include 'config.php';

file_put_contents(DOCROOT . 'logs/log.txt', $_SERVER['REQUEST_URI'] . "\t" . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);

$request = new \App\Router();
$request->distribute();
?>