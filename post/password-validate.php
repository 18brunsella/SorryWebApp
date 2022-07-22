<?php
$open = true;
require '../lib/site.inc.php';

$controller = new Game\PasswordValidateController($site,$_POST);
header("location: " . $controller->getRedirect());
