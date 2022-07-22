<?php


require __DIR__ . "/../vendor/autoload.php";

session_start();

define('GAME_SESSION', 'game');

if (!isset($_SESSION[GAME_SESSION])) {
    $_SESSION[GAME_SESSION] = new Game\Game();
}
$user = null;
$game = $_SESSION[GAME_SESSION];
