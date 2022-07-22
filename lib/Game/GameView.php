<?php


namespace Game;


/**
 * View class for the Game
 * @package Game
 */
class GameView {
    /**
     * @var Game The game object.
     */
    private $game;

    private $turn;
    private $turn_color;
    /**
     * GameView constructor.
     *
     * @param Game $game The game object.
     */
    public function __construct(Game $game,User $user, Site $site) {
        $this->game = $game;
        $games = new Games($site);
        $game->setPlayerTurn($games->getTurn($user));
        $key = $user->getGameKey();
        $state = $games->getGameState($key);
        //$this->game = json_decode($state, true);
        $turn = $games->isPlayerTurn($user);
        $this->turn_color = $this->playerName($games->getTurn($user));
        $this->turn = 'players turn';
        if($turn){
            $this->turn = 'your turn';
        }
        if(!$turn) {
            $this->game->loadGameState($state);
        }
    }

    /**
     * @return string The HTML for the game page.
     */
    public function present(): string {
        $game_board = $this->game->getBoard();

        $html = '<form method="post" action="post/game-post.php"> <div class="game"> <div class="board">';

        for ($i = 0; $i < 16; $i++) {
            $html .= '<div class="row">';
            for ($j = 0; $j < 16; $j++) {
                $tile = $game_board->getTile($i, $j);
                if (gettype($tile) != "integer") {
                    $inside = $this->insideCell($tile);
                    $html .= '<div class ="cell">';
                    $html .= '<button type="submit" name="cell" value="' . $i . ', ' . $j . '">';
                    $html .= $inside;
                    $html .= '</button></div>';

                } else {
                    $html .= '<div class="cell"></div>';
                }
            }
            $html .= '</div>';
        }

        // Present the 'Draw Card' button and the back of the card image on the card deck pile
        if (!($this->game->getCardDrawn()) && $this->turn == 'your turn') {
            $html .= '<input type="submit" id = "drawButton" name="draw" class="button" value="Draw Card"/>';
        } else {
            $html .= '<input type="submit" id = "grayedDraw" name="" class="button" value="Draw Card"/>';
        }

        $html .= '<div class="card"><img id="cardBack" src="images/card_back.png" width = 192 height = 256"></div>';
        //$html .= '<input type="submit" id = "resetButton" name="reset" class="button" value="New Game"/>';

        if ($this->game->isTurnOver()) {
            $html .= '<input type="submit" id = "doneButton" name="done" class="button" value="Done!"/>';
        } else {
            $html .= '<input type="submit" id = "grayedDone" name="" class="button" value="Done!"/>';
        }

        //$html .= '<p class="turn">Player turn: ' . $this->playerName($this->game->getPlayerTurn()) . '</p>';
        $html .= '<p class="turn">' . $this->turn.' ('.$this->turn_color.')</p>';

        $html .= $this->displayCard();

        if ($this->game->isGameOver()) {
            $player = $this->game->getPlayerTurn();
            $winner = "Yellow";

            if ($player == 0) {
                $winner = "Yellow";
            } else if ($player == 1) {
                $winner = "Green";
            } else if ($player == 2) {
                $winner = "Red";
            } else if ($player == 3) {
                $winner = "Blue";
            }


            $html .= <<<HTML
<div class="game-over-modal">
    <p class="game-over-text">Game Over!</p>
    <p class="player-win">$winner Wins!</p>
    <input type="submit" id = "gameBack" name="quit" class="button" value="Back to menu"/>
</div>
HTML;
        }

        $html .= '</div></div></form>';
        return $html;
    }

    /**
     * Get HTML for a tile
     *
     * @param Tile $tile
     *
     * @return string The HTML for this tile
     */
    public
    function insideCell(Tile $tile): string {
        $pawn = $tile->getContains();
        if (!isset($pawn)) {
            return "";
        } else {
            return '<img class="piece" src="' . $pawn->getImage() . '">';
        }
    }

    /**
     * @param int $color Color number.
     *
     * @return string The players name.
     */
    public
    function playerName(int $color): string {
        if ($color == 0) {
            return "yellow";
        } else if ($color == 1) {
            return "green";
        } else if ($color == 2) {
            return "red";
        } else if ($color == 3) {
            return "blue";
        }

        return '';
    }

    /**
     * @return string The HTML for a card.
     */
    public
    function displayCard(): string {
        if ($this->game->getDiscard() != null) {
            $discard = $this->game->getDiscard()->getImage();
            return '<p class="card"><img id="discard" src=' . $discard . ' width = 192 height = 256"></p>';
        } else {
            return '<p class="card"></p>';
        }
    }
}
