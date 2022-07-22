<?php


namespace Game;


/**
 * Class Player
 * @package Game
 */
class Player {
    /**
     * @var int The player's color.
     */
    private $color;
    /**
     * @var array The player's pawns.
     */
    private $pawns = [];

    /**
     * Player constructor.
     *
     * @param int $color Color of the player.
     */
    public function __construct($color) {
        $this->color = $color;
    }

    /**
     * Add a pawn to this player.
     *
     * @param Pawn $pawn
     */
    public function addPawn($pawn) {
        $this->pawns[] = $pawn;
    }

    /**
     * Get one of the player's pawns.
     *
     * @param array-key $ndx Index of the pawn.
     *
     * @return Pawn
     */
    public function getPawn($ndx) {
        return $this->pawns[$ndx];
    }
}
