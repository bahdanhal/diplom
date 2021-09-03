<?php
require_once 'config.php';
use App\App;
use Auth\Auth;

$app = new App();
$auth = new Auth($app->user);
$auth->signin();