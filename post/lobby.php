<?php
$open = true;
require '../lib/site.inc.php';

$controller = new Game\LobbyController($game, $site, $user, $_POST);
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//echo $controller->getRedirect();

header("Location: " . $controller->getRedirect());
