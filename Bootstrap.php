<?php
require_once ROOT_DIR . '/vendor/autoload.php';
use App\App;
session_start();
$app = new App();
$app->run();
//получаем идентификатор сессии из куков
//сравниваем с дб
//если да выводим хелло
//если нет выводим формы
    