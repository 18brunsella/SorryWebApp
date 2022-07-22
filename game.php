<?php


require __DIR__ . '/lib/site.inc.php';

//if (!$game->isGameReady()) {
 //   header("location:index.php");
//}

$view = new Game\GameView($game,$user,$site);
//if(!$view->protect($site, $user)) {
//    header("location: " . $view->getProtectRedirect());
//    exit;
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sorry!</title>
    <link rel="stylesheet" href="lib/game.css" type="text/css">
    <?php
    $key = $user->getGameKey();

    $html = <<<HTML
<script>
var key = '$key';
        /**
         * Initialize monitoring for a server push command.
         * @param key Key we will receive.
         */
        function pushInit(key) {
            var conn = new WebSocket('wss://webdev.cse.msu.edu/ws');
            conn.onopen = function (e) {
                console.log("Connection to push established!");
                console.log(key);
                conn.send(key);
            };

            conn.onmessage = function (e) {
                try {
                    var msg = JSON.parse(e.data);
                    if (msg.cmd === "reload") {
                        location.reload();
                    }
                } catch (e) {
                }
            };
        }
        pushInit(key);
    </script>
HTML;

    echo $html;
    ?>

</head>
<body>
<?php echo $view->present(); ?>
</body>
</html>
