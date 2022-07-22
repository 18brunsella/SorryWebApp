<?php
require 'lib/site.inc.php';
$view = new Game\LobbyView($site, $game, $user);
if(!$view->protect($site, $user)) {
    header("location: " . $view->getProtectRedirect());
    exit;
}
if($view->isGameRedirect($site, $user)){
    header("location: " . $view->getRedirect());
    exit;
}

$game_id = $view->isLobbyRedirect($site, $user);
if($game_id === null){
    header("location: " . $view->getRedirect());
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $view->head(); ?>
</head>
<body>
    <?php echo $view->header(); ?>
    <?php echo $view->present(); ?>

    <script>
        function pushInit(key) {
            let conn = new WebSocket("wss://webdev.cse.msu.edu/ws");

            conn.onopen = () => {
                console.log(`Connection to websocket established with key '${key}'`);
                conn.send(key);
            };

            conn.onmessage = (event) => {
                try {
                    let msg = JSON.parse(event.data);
                    if (msg.cmd === "reload") {
                        location.reload();
                    }
                } catch (e) {
                    console.error("WebSocket error:", e);
                }
            };
        }

        pushInit("<?php echo $user->getGameKey(); ?>");
    </script>
</body>
</html>
