<?php


namespace Game;


/**
 * Class Game.
 * The main class for most game functions.
 * @package Game
 */
class Game {
    /**
     * @var array The players.
     */
    private $players = [];
    /**
     * @var Cards The cards.
     */
    private $cards;
    /**
     * @var Board The board.
     */
    private $board;
    /**
     * @var array The discarded cards.
     */
    private $discard;
    /**
     * @var int Which players turn it is.
     */
    private $player_turn = 0;
    /**
     * @var bool Player turn selected pawn.
     */
    private $selected_pawn = false;
    /**
     * @var Tile Player turn tile.
     */
    private $tile_from;
    /**
     * @var bool If the game is ready.
     */
    private $game_ready = false;
    /**
     * @var bool Determine game state for a turn.
     */
    private $card_drawn = false;
    /**
     * @var bool If a pawn has been moved.
     */
    private $pawn_moved = false;
    /**
     * @var bool If the player's turn is over.
     */
    private $turn_over = false;
    /**
     * @var int How many tiles the pawn moved.
     */
    private $tiles_moved = 0;
    /**
     * @var int How many tiles are reachable.
     */
    private $reachable_count = 0;
    /**
     * @var int How many moves are available when player is doing card 7.
     */
    private $card_7_moves_available = 7;
    /**
     * @var int How many pawns are available to move when player is doing card 7.
     */
    private $card_7_pawns_available_to_move = 2;

    /**
     * Game constructor.
     *
     * @param int $seed Seed for rng (deck of cards, etc.)
     */
    public function __construct($seed = null) {
        if (!isset($seed)) {
            srand(time());
        } else {
            srand($seed);
        }

        $this->createBoard();
        $this->createDeck();
    }

    /**
     * Creates a board.
     */
    public function createBoard() {
        $this->board = new Board();
    }

    /**
     * Creates a deck.
     */
    public function createDeck() {
        $this->cards = new Cards();
    }

    /**
     * Adds a player to an array of players
     *
     * @param array-key $player The player.
     */
    public function addPlayer($player) {
        $this->players[$player] = new Player($player);
    }

    /**
     * Draws a card.
     */
    public function drawCard() {
        $this->discard = $this->cards->getTop();
        $this->card_drawn = true;
    }

    /**
     * Determines if a player has moves. If not, the player can end their turn.
     *
     * @return bool If the player has moves remaining.
     */
    public function checkHasMoves() {
        $card_num = $this->processCard();

        if ($card_num == 0) {
            return $this->sorryAvail();
        }

        $count = 0;
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $tile = $this->board->getTile($i, $j);
                if (gettype($tile) != "integer") {
                    if ($tile->getContains() !== null) {
                        if ($tile->getContains()->getColor() == $this->player_turn &&
                            !$this->processCardRule($tile, $card_num)) {
                            $count++;

                        }
                    }
                }
            }
        }

        if ($count == 4) {
            return false;
        }

        if ($this->reachable_count == 0) {
            return false;
        }

        return true;
    }

    /**
     * Processes the card and returns a number of the card.
     *
     * @return int The card's number.
     */
    public function processCard() {
        $card_num = 0;
        if ($this->discard !== null) {
            $card_num = $this->discard->getDescription();
        }
        return $card_num;
    }

    public function sorryAvail() {
        $pawnInHome = false;

        $home0 = $this->board->getStartSpaces($this->player_turn, 0);
        $home1 = $this->board->getStartSpaces($this->player_turn, 1);
        $home2 = $this->board->getStartSpaces($this->player_turn, 2);
        $home3 = $this->board->getStartSpaces($this->player_turn, 3);

        if (gettype($home0) != 'integer' && $home0->getContains() !== null) {
            $pawnInHome = true;
        }
        if (gettype($home1) != 'integer' && $home1->getContains() !== null) {
            $pawnInHome = true;
        }
        if (gettype($home2) != 'integer' && $home2->getContains() !== null) {
            $pawnInHome = true;
        }
        if (gettype($home3) != 'integer' && $home3->getContains() !== null) {
            $pawnInHome = true;
        }

        if ($pawnInHome == false) {
            return false;
        }

        $otherColor = false;
        $tiles = [[0, 0], [0, 1], [0, 2], [0, 3], [0, 4], [0, 5], [0, 6], [0, 7], [0, 8], [0, 9], [0, 10], [0, 11], [0, 12], [0, 13], [0, 14], [0, 15],
                  [1, 15], [2, 15], [3, 15], [4, 15], [5, 15], [6, 15], [7, 15], [8, 15], [9, 15], [10, 15], [11, 15], [12, 15], [13, 15], [14, 15], [15, 15],
                  [15, 0], [15, 1], [15, 2], [15, 3], [15, 4], [15, 5], [15, 6], [15, 7], [15, 8], [15, 9], [15, 10], [15, 11], [15, 12], [15, 13], [15, 14],
                  [1, 0], [2, 0], [3, 0], [4, 0], [5, 0], [6, 0], [7, 0], [8, 0], [9, 0], [10, 0], [11, 0], [12, 0], [13, 0], [14, 0]];

        foreach ($tiles as $t) {
            $tile = $this->board->getTile($t[0], $t[1]);
            if ($tile->getContains() !== null && ($tile->getContains()->getColor() != $this->player_turn)) {
                $otherColor = true;
            }
        }

        if ($otherColor) {
            return true;
        }

        return false;
    }

    /**
     * Process the card's rule and set necessary flags.
     *
     * @param Tile $tile_clicked The tile clicked by the user.
     * @param int $card_num The current card number.
     *
     * @return bool
     */
    public function processCardRule($tile_clicked, $card_num) {
        if ($this->pawn_moved) {
            return false;
        }

        $this->resetTileFlags();
        if ($tile_clicked->getContains() !== null) {
            if ($tile_clicked->getContains()->getOnStart($tile_clicked->getRow(), $tile_clicked->getCol())) {
                if ($card_num == 1 || $card_num == 2) {
                    $card_num = 1;
                } else {
                    return false;
                }
            }
        }

        switch ($card_num) {
            /*
             * Either move a pawn from Start or move a pawn one space forward.
             */
            case 1:
                $tile_clicked->searchReachable(1);
                break;
            /*
             * Either move a pawn from Start or move a pawn two spaces forward. Drawing a two entitles the player to
             * draw again at the end of their turn. If the player cannot use a two to move, he or she can
             * still draw again.
             */
            case 2:
                $tile_clicked->searchReachable($card_num);
                break;
            /*
             * Move a pawn four spaces backward.
             */
            case 4:
                $tile_clicked->searchReachable(4, false);
                break;
            /*
             * Split movement of 7 spaces between two pawns.
             */
            case 7:
                if ($this->card_7_pawns_available_to_move == 0) {
                    // Stop if no more moves are available.
                    break;
                } else if ($this->card_7_pawns_available_to_move == 1) {
                    // If only one pawn can be moved, force it to move the remaining amount available.
                    $tile_clicked->searchReachable($this->card_7_moves_available);
                } else {
                    // If multiple pawns can be moved, allow any move up to the remaining amount available.
                    for ($i = 1; $i <= $this->card_7_moves_available; $i++) {
                        $tile_clicked->searchReachable($i);
                    }
                }

                break;
            /*
             * Move a pawn ten spaces forward or one space backward. If none of a player's pawns can move
             * forward 10 spaces, then one pawn must move back one space.
             */
            case 10:
                $tile_clicked->searchReachable(10);
                $tile_clicked->searchReachable(1, false);
                break;
            /*
             *  Move a pawn ~(2,4,10, etc,) spaces forward
             */
            case 7:
                $this->tiles_moved = 7;
            default:
                $tile_clicked->searchReachable($card_num);
        }

        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $tile = $this->board->getTile($i, $j);
                if (gettype($tile) != "integer") {
                    if ($tile->getReachable()) {
                        $this->reachable_count++;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Reset tile flags.
     */
    public function resetTileFlags() {
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $tile = $this->board->getTile($i, $j);
                if (gettype($tile) != "integer") {
                    $tile->setReachable(false);
                    $tile->setOnPath(false);
                    $tile->setBlocked(false);
                }
            }
        }
    }

    /**
     * Determines what move to make.
     *
     * @param int $row Row of selected tile
     * @param int $col Column of selected tile
     */
    public function processMove($row, $col) {
        $homeClicked = false;
        $tile_clicked = $this->board->getTile($row, $col);
        // Player turn started, they need to select a pawn
        $card_num = $this->processCard();

        if ($tile_clicked->getContains() !== null && !$this->pawn_moved) {
            if ($tile_clicked->getContains()->getColor() == $this->player_turn) {
                $this->selected_pawn = true;
                $this->tile_from = $tile_clicked;
                $this->processCardRule($tile_clicked, $card_num);
            }
        }

        if ($card_num == 0 && $this->tile_from !== null) {
            $home0 = $this->board->getStartSpaces($this->player_turn, 0);
            $home1 = $this->board->getStartSpaces($this->player_turn, 1);
            $home2 = $this->board->getStartSpaces($this->player_turn, 2);
            $home3 = $this->board->getStartSpaces($this->player_turn, 3);


            if ($this->tile_from->getRow() == $home0->getRow() && $this->tile_from->getCol() == $home0->getCol()) {
                $homeClicked = true;
            }
            if ($this->tile_from->getRow() == $home1->getRow() && $this->tile_from->getCol() == $home1->getCol()) {
                $homeClicked = true;
            }
            if ($this->tile_from->getRow() == $home2->getRow() && $this->tile_from->getCol() == $home2->getCol()) {
                $homeClicked = true;
            }
            if ($this->tile_from->getRow() == $home3->getRow() && $this->tile_from->getCol() == $home3->getCol()) {
                $homeClicked = true;
            }
        }

        if ($this->selected_pawn) {
            // Ensure player isn't trying to move into another safe space
            if ($tile_clicked->getType() == Tile::SAFE && $tile_clicked->getSafeColor() != $this->player_turn) {
                return;
            }
            // Check if pawn already is there
            if ($tile_clicked->getReachable() && $card_num != 0) {
                if ($tile_clicked->getContains() !== null) {
                    if ($tile_clicked->getContains()->getColor() != $this->player_turn) {
                        $this->backToStart($tile_clicked->getContains(), $tile_clicked->getContains()->getColor());
                        $tile_clicked->setContains(null);
                    }
                }

                $sliding = $this->checkSlide($tile_clicked);

                // Check if pawn is on the start of the slide
                if (!$sliding) {
                    $tile_clicked->setContains($this->tile_from->getContains());
                }

                if ($card_num == 2) {
                    $this->reachable_count = 0;
                    $this->card_drawn = false;
                    $this->pawn_moved = false;
                } else if ($card_num == 7) {
                    // Player has moved a pawn by this point.
                    // Subtract one from pawns available to move.
                    $this->card_7_pawns_available_to_move--;
                    // Subtract distance moved from moves available.
                    $this->card_7_moves_available -= Tile::distance($this->tile_from, $tile_clicked);

                    // Set turn over if either conditions are no longer met.
                    if ($this->card_7_pawns_available_to_move == 0 || $this->card_7_moves_available == 0) {
                        $this->setTurnOver(true);
                    }
                } else {
                    $this->pawn_moved = true;
                }


                $this->tile_from->setContains(null);
                $this->tile_from = null;
                $this->selected_pawn = false;
            }
        }

        // Sorry Card
        if ($homeClicked == true && $tile_clicked->getContains() !== null && $card_num == 0 && $this->selected_pawn && $tile_clicked->getContains()->getColor() != $this->tile_from->getContains()->getColor() && $this->isValidSwap($tile_clicked)) {
            $this->sorry($tile_clicked);
        }

        if ($tile_clicked->getContains() !== null && $card_num == 11 && $this->selected_pawn && $this->pawn_moved == false) {
            $this->swapPawns($tile_clicked);
        }
    }

    /**
     * Send a pawn back to start.
     *
     * @param Pawn $pawn The pawn to send back.
     * @param array-key $color The player whose pawn is being sent back.
     */
    private function backToStart($pawn, $color) {
        for ($i = 0; $i < 4; $i++) {
            $startLocation = $this->board->getStartSpaces($color, $i);
            if ($startLocation->getContains() === null) {
                $pawn->setStartTile($startLocation->getRow(), $startLocation->getCol());
                $startLocation->setContains($pawn);
                return;
            }
        }
    }

    /**
     * Checks whether a pawn is on a slide.
     *
     * @param $tile_clicked
     *
     * @return bool
     */
    public function checkSlide($tile_clicked) {
        $slide = $this->board->getSlideLocations();
        $pawnColor = $this->tile_from->getContains()->getColor();
        $pawnToSlide = $this->tile_from->getContains();
        for ($i = 0; $i < count($slide); $i++) {
            $startSlideTile = $slide[$i][0];
            // If it lands on the first slide tile and pawn color != slide color
            if ($startSlideTile === $tile_clicked && ($pawnColor + $pawnColor != $i && $pawnColor + ($pawnColor + 1) != $i)) {
                for ($j = 0; $j < count($slide[$i]); $j++) {
                    if ($slide[$i][$j]->getContains() !== null) {
                        $pawnToSend = $slide[$i][$j]->getContains();
                        $pawnToSendColor = $pawnToSend->getColor();
                        if ($pawnToSlide != $pawnToSend) {
                            $this->backToStart($pawnToSend, $pawnToSendColor);
                            if (end($slide[$i]) != $slide[$i][$j]) {
                                $slide[$i][$j]->setContains(null);
                            }
                        }
                    }
                }
                $endTile = end($slide[$i]);
                $endTile->setContains($pawnToSlide);
                return true;
            }
        }

        return false;
    }

    public function isValidSwap($tile_clicked) {
        if ($tile_clicked->getContains() !== null && $this->tile_from !== null && $this->ProcessCard() == 11) {
            if ($tile_clicked->getType() == Tile::NORM && $this->tile_from->getType() == Tile::NORM) {
                if ($this->tile_from->getContains() !== null) {
                    return true;
                }

            }
        } else if ($tile_clicked->getContains() !== null && $this->tile_from !== null) {
            if ($tile_clicked->getType() == Tile::NORM) {
                if ($this->tile_from->getContains() !== null) {
                    return true;
                }
            }
        }

        return false;
    }

    public function sorry($tile_clicked) {
        $this->backToStart($tile_clicked->getContains(), $tile_clicked->getContains()->getColor());

        // Implementation when pawn swaps with a pawn already on a slide
        $sliding = $this->checkSlide($tile_clicked);
        if (!$sliding) {
            $tile_clicked->setContains($this->tile_from->getContains());
        }

        $this->tile_from->setContains(null);
        $this->selected_pawn = false;
        $this->tile_from = null;
        $this->pawn_moved = true;
    }

    public function swapPawns($tile_clicked) {
        if ($tile_clicked->getContains() !== null) {
            if ($tile_clicked->getContains()->getColor() != $this->player_turn && $this->isValidSwap($tile_clicked)) {
                $pawn = $this->tile_from->getContains();
                $this->tile_from->setContains($tile_clicked->getContains());
                $tile_clicked->setContains($pawn);

                // Implementation when a pawn swaps with pawn on a slide
                $this->setTileFrom($tile_clicked);
                $sliding = $this->checkSlide($tile_clicked);
                if ($sliding) {
                    $this->tile_from->setContains(null);
                }

                $this->selected_pawn = false;
                $this->tile_from = null;
                $this->pawn_moved = true;
            }
        }
    }

    /**
     * Checks the player at the end of their turn whether all their pawns are in the home spaces or not.
     *
     * @return bool whether game is over or not
     */
    public function isGameOver() {
        $count = 0;
        for ($i = 0; $i < 4; $i++) {
            $home_space = $this->board->getHomeSpaces($this->player_turn, $i);
            if ($this->board->getTile($home_space->getRow(), $home_space->getCol())->getContains() !== null) {
                $count++;
            }
        }
        if ($count == 4) {
            return true;
        }
        return false;
    }

    /**
     * Increments the turn based on total players
     */
    public function incrementPlayerTurn() {
        $this->player_turn = ($this->player_turn + 1) % count($this->players);
        $this->setTurnOver(false);
        $this->reachable_count = 0;
    }

    public function incrementPlayerTurnOnline($turn){
        $player_turn = ($turn + 1) % count($this->players);
        $this->setTurnOver(false);
        $this->reachable_count = 0;
        return $player_turn;
    }
    /**
     * Resets the game but not session
     */
    public function newGame() {
        $this->players = [];
        $this->cards = null;
        $this->discard = null;
        $this->player_turn = 0;
        $this->card_drawn = false;
        $this->pawn_moved = false;
        $this->card_7_pawns_available_to_move = 2;
        $this->card_7_moves_available = 7;
        $this->tiles_moved = 0;
        $this->reachable_count = 0;

        $this->createBoard();
        $this->createDeck();
        //$this->initializePlayerPawns();
    }

    /**
     * Initializes the player pawns, adds them to the start spaces
     */
    public function initializePlayerPawns() {
        for ($i = 0; $i < 4; $i++) {
            if (isset($this->players[$i])) {
                $this->game_ready = true;
                $this->board->buildStart($i);
                $player = $this->players[$i];
                for ($j = 0; $j < 4; $j++) {
                    $row = $this->board->getStartSpaces($i, $j)->getRow();
                    $col = $this->board->getStartSpaces($i, $j)->getCol();
                    $pawn = new Pawn($i, $row, $col);
                    $player->addPawn($pawn);
                    $this->board->getStartSpaces($i, $j)->setContains($pawn);
                    $this->board->buildHome($i);
                }
            }
        }
    }

    public function saveGameState(){
        $card_state = null;
        $player_turn = $this->player_turn;
        if($this->getDiscard()){
            $card_state = $this->getDiscard()->getDescription();
            if($card_state === 0){
                $card_state = 'sorry';
            }
        }
        $win_state = $this->isGameOver();
        $board_state = [[]];
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $tile = $this->board->getTile($i, $j);
                $tile_state = 1;
                if (gettype($tile) != "integer" && $tile->getContains() !== null) {
                    $pawn_state = $this->board->getTile($i,$j)->getContains();
                    $tile_state = ['color'=>$pawn_state->getColor(), 'start_row' => $pawn_state->getStartRow(),
                                    'start_col'=>$pawn_state->getStartCol()];
                }
                $board_state[$i][$j] = $tile_state;
            }
        }
        $json_export = json_encode(['card_state'=>$card_state,'win_state'=>$win_state,'board_state'=>$board_state,'player_state'=>$player_turn]);
        return $json_export;
    }

    public function loadGameState($json_export) {
        $json_decoded = json_decode($json_export, true);
        $this->setDiscard(null);
        $card_num = $json_decoded['card_state'];
        if($card_num !== null) {
            $new_card = new Card($card_num);
            $this->setDiscard($new_card->getDescription());
        }
        $this->player_turn = $json_decoded['player_state'];


        //load tiles back in
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $tile = $this->board->getTile($i, $j);
                if (gettype($tile) != "integer") {
                    //reset any board tiles so that they are empty
                    if($tile->getContains() !== null) {
                        $tile->setContains(null);
                    }
                    //see if the current spot in the json_array has info
                    $tile_state_loaded = $json_decoded['board_state'][$i][$j];
                    if($tile_state_loaded != 1) {
                       $tile->setContains(new Pawn($tile_state_loaded['color'], $tile_state_loaded['start_row'], $tile_state_loaded['start_col']));
                    }
                }
            }
        }

        $this->isGameOver();
    }

    /**
     * @param int $reachable_count
     */
    public function setReachableCount($reachable_count) {
        $this->reachable_count = $reachable_count;
    }

    /**
     * @return bool Is the game ready.
     */
    public function isGameReady() {
        return $this->game_ready;
    }

    /**
     * Determines if turn is over for the player.
     *
     * @return bool If the turn is over.
     */
    public function isTurnOver() {
        if ($this->card_drawn && $this->pawn_moved) {
            return true;
        }
        return false;
    }

    /**
     * Sets two booleans to the turn over status.
     *
     * @param bool $turn_over
     */
    public function setTurnOver($turn_over) {
        $this->turn_over = $turn_over;
        if (!$turn_over) {
            $this->card_drawn = false;
            $this->pawn_moved = false;
        } else {
            $this->card_drawn = true;
            $this->pawn_moved = true;
            $this->card_7_moves_available = 7;
            $this->card_7_pawns_available_to_move = 2;
        }
    }

    public function getCardDrawn() {
        return $this->card_drawn;
    }

    public function setCardDrawn($card_drawn) {
        $this->card_drawn = $card_drawn;
    }

    public function getPawnMoved() {
        return $this->pawn_moved;
    }

    public function setPawnMoved($pawn_moved) {
        $this->pawn_moved = $pawn_moved;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getBoard() {
        return $this->board;
    }

    public function getCards() {
        return $this->cards;
    }

    public function getDiscard() {
        return $this->discard;
    }

    public function setDiscard($num) {
        if($num !== null) {
            $card = new Card($num);
            if($num == 0){
                $card = new Card('sorry');
            }
            $this->discard = $card;
        }
    }

    public function getPlayerTurn() {
        return $this->player_turn;
    }

    public function isPawnSelected() {
        return $this->selected_pawn;
    }

    public function getTileFrom() {
        return $this->tile_from;
    }

    public function setTileFrom($tile_from) {
        $this->tile_from = $tile_from;
    }

    /**
     * @param int $player_turn
     */
    public function setPlayerTurn($player_turn)
    {
        $this->player_turn = $player_turn;
    }

}
