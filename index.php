<?php
$open = true;
require 'lib/site.inc.php';
$view = new Game\LoginView($_SESSION, $_GET, $site);
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