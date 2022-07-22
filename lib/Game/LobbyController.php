<?php


namespace Game;


class LobbyController {
    public function __construct(Game $game, Site $site, User $user, $post) {
        $this->game = $game;
        $root = $site->getRoot();
        $this->redirect = "$root/game.php";
        if(isset($post['Delete'])) {
            $games = new Games($site);
            if($games->isPlayerHost($user)){
                $games->removeGame($user);
                $this->redirect = "$root/matchmaking.php";
            }
        }
        if(isset($post['Submit'])){
            $games = new Games($site);
            if($games->isPlayerHost($user)){
                $this->createGame($games->get($user->getGameKey())[0]);
                $games->updateGameState($game->saveGameState(),$game->getPlayerTurn(), games::GAME_STARTED, $user->getGameKey());
            }
            /*
             * PHP code to cause a push on a remote client.
             */
            $msg = json_encode(array('key'=>$user->getGameKey(), 'cmd'=>'reload'));

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            $sock_data = socket_connect($socket, '127.0.0.1', 8078);
            if(!$sock_data) {
                echo "Failed to connect";
            } else {
                socket_write($socket, $msg, strlen($msg));
            }
            socket_close($socket);
        }
    }

    public function createGame($players){
        $this->game->newGame();
        $this->game->addPlayer(0);

        if($players['player1'] !== null){
            $this->game->addPlayer(1);
        }
        if($players['player2'] !== null){
            $this->game->addPlayer(2);
        }
        if($players['player3'] !== null){
            $this->game->addPlayer(3);
        }
        $this->game->initializePlayerPawns();

    }
    private $redirect;
    private $game;
    public function getRedirect()
    {
        return $this->redirect;
    }

}
