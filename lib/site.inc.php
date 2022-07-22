<?php


require __DIR__ . "/../vendor/autoload.php";

// Create the Site object
$site = new Game\Site();
$localize = require "localize.inc.php";
if (is_callable($localize)) {
    $localize($site);
}

// Start the session
session_start();

//GAMEPLAY
define('GAME_SESSION', 'game');

if (!isset($_SESSION[GAME_SESSION])) {
    $_SESSION[GAME_SESSION] = new Game\Game();
}

$game = $_SESSION[GAME_SESSION];


$user = null;
if (isset($_SESSION[Game\User::SESSION_NAME])) {
    $user = $_SESSION[Game\User::SESSION_NAME];
}

// Redirect if page is not open and user is not logged in
if ((!isset($open) || !$open) && $user === null) {
    $root = $site->getRoot();
    header("location: $root/");
    exit;
}

