<?php
$open = true;
require '../lib/site.inc.php';

$controller = new Game\NewUserController($site, $_POST, $_SESSION);

header("Location: " . $controller->getRedirect());
