<?php
$open = true;
require 'lib/site.inc.php';
$view = new Game\InstructionsView($game);
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
