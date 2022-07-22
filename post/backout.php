<?php
require '../lib/site.inc.php';

$games = new \Game\Games($site);
$games->removeUser($user);

$root = $site->getRoot();
header("location: " . "$root/matchmaking.php");
exit;