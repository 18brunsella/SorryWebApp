<?php
require 'lib/site.inc.php';
$view = new Game\MatchmakingView($game, $site);

//if(!$view->protect($site, $user)) {
//    header("location: " . $view->getProtectRedirect());
//    exit;
//}
$game_id = $view->isLobbyRedirect($site, $user);
if($game_id !== null){
    //$user->setGameKey($game_id);
    header("location: " . $view->getRedirect());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $view->head();
    $key = \Game\Site::LOBBY_KEY;

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
<?php echo $view->header(); ?>
<?php echo $view->present(); ?>
</body>
</html>
