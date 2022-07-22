<?php
$open = true;
require '../lib/site.inc.php';

$controller = new Game\HomePageController($game, $_POST);

if ($controller->isValid()) {
    header("location: ../game.php");
} else {
    header("location: ../index.php");
}

exit;
