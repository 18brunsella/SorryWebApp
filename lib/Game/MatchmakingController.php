<?php


namespace Game;


class MatchmakingController {
    public function __construct(Site $site, User $user, $post) {
        $root = $site->getRoot();
        $this->redirect = "$root/matchmaking.php";
        if(isset($post['create'])){
            $games = new Games($site);
            $games->newGame($user);
            $this->redirect = "$root/lobby.php";

            /*
             * PHP code to cause a push on a remote client.
             */
            $msg = json_encode(array('key'=>SITE::LOBBY_KEY, 'cmd'=>'reload'));

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            $sock_data = socket_connect($socket, '127.0.0.1', 8078);
            if(!$sock_data) {
                echo "Failed to connect";
            } else {
                socket_write($socket, $msg, strlen($msg));
            }
            socket_close($socket);
        }
        else if (isset($post['join'])){
            $games = new Games($site);
            $games->addUserToGame($user, $post['chosenGame']);
            $this->redirect = "$root/matchmaking.php";

            // Update lobby page when a user joins
            $msg = json_encode(["key" => $user->getGameKey(), "cmd" => "reload"]);
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $socket_data = socket_connect($socket, "127.0.0.1", 8078);
            if (!$socket_data) {
                echo "Failed to connect to WebSocket";
            } else {
                socket_write($socket, $msg, strlen($msg));
            }
        }
    }

    private $redirect;

    public function getRedirect() {
        return $this->redirect;
    }	// Page we will redirect the user to.
}
