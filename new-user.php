<?php
$open = true;
require 'lib/site.inc.php';
$view = new Game\NewUserView($_GET, $_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $view->head(); ?>
</head>
<body>
    <?php echo $view->header(); ?>
    <?php echo $view->present(); ?>
</body>
</html>
