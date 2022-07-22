<?php
require '../lib/site.inc.php';

unset($_SESSION[Game\User::SESSION_NAME]);
$user = null;

header("location: " . $site->getRoot());
exit;