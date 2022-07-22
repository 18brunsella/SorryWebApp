<?php
$open = true;
require '../lib/site.inc.php';

use Game\GameController;

$controller = new GameController($game, $site ,$_POST,$user);

if ($controller->getIsReset()) {
    header("location: ../matchmaking.php");
    unset($_SESSION[GAME_SESSION]);
    exit;

}

header("location: ../game.php");

exit;
