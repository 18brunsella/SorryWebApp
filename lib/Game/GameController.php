<?php


namespace Game;


/**
 * Controller for the game
 * @package Game
 */
class GameController {
    /**
     * @var int The row from this POST request.
     */
    private $row;
    /**
     * @var int The column from this POST request.
     */
    private $col;
    /**
     * @var Game The game object.
     */
    private $game;

    /**
     * @var bool Whether the game is being reset.
     */
    private $is_reset;

    /**
     * GameController constructor.
     *
     * @param Game $game The game object.
     * @param array $post The POST array.
     */
    public function __construct(Game $game, Site $site ,array $post, User $user) {
        $this->game = $game;
        $games = new Games($site);
        // If the 'Draw Card' button is clicked
        if (isset($post['draw'])) {
            if ($game->getCards()->getCount() == 0) {
                $game->createDeck();
            }
            $game->drawCard();
            if (!$game->checkHasMoves()) {
                $game->setTurnOver(true);

            }
        }
        $key = $user->getGameKey();
        $next_turn = $games->getTurn($user);
        $status = Games::GAME_STARTED;
        if ($game->isGameOver()) {
            $status = Games::GAME_COMPLETE;
        }
        if (isset($post['done'])) {
            $curr_turn = $games->getTurn($user);
            $next_turn = $game->incrementPlayerTurnOnline($curr_turn);
            $game->setTurnOver(false);
            //server upload

            $state = $games->getGameState($key);
            //$this->game = json_decode($state, true);
            $this->game->loadGameState($state);
            //$this->game = unserialize($state);
        }
        if($this->game->getCardDrawn()) {
            if (isset($post['cell'])) {
                $tile_val = explode(',', strip_tags($post['cell']));
                $this->row = +$tile_val[0];
                $this->col = +$tile_val[1];
                if (!$game->getPawnMoved()) {
                    $game->processMove($this->row, $this->col);
                }
            }
        }
        if (isset($post['reset'])) {
            $this->game = $this->game->newGame();
        }

        if (isset($post['quit'])) {
            $this->is_reset = true;
            header("location: ../matchmaking.php");
        }
        $games->updateGameState($game->saveGameState(), $next_turn, $status, $key);
        /*
* PHP code to cause a push on a remote client.
*/

        if(!$this->game->isGameOver()) {
            $msg = json_encode(array('key' => $key, 'cmd' => 'reload'));

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            $sock_data = socket_connect($socket, '127.0.0.1', 8078);
            if (!$sock_data) {
                echo "Failed to connect";
            } else {
                socket_write($socket, $msg, strlen($msg));
            }
            socket_close($socket);
        }
    }

    /**
     * @return int Get the row from this POST request.
     */
    public function getRow() {
        return $this->row;
    }

    /**
     * @return int Get the column from this POST request.
     */
    public function getCol() {
        return $this->col;
    }

    /**
     * @return bool Get if the game is being reset.
     */
    public function getIsReset() {
        return $this->is_reset;
    }
}
