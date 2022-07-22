<?php
$open = true;
require '../lib/site.inc.php';

$controller = new Game\MatchmakingController($site, $user, $_POST);

header("Location: " . $controller->getRedirect());
