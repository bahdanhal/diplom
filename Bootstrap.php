<?php
require_once ROOT_DIR . '/vendor/autoload.php';
use App\App;
session_start();
$app = new App();
$app->run();
