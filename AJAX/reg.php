<?php
ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);
define('DB_FILE', 'db.xml');
require_once __DIR__. '/../vendor/autoload.php';
use App\App;
$app = new App();
$app->reg();