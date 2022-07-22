<?php


namespace Game;


/**
 * Class Pawn
 * @package Game
 */
class Pawn {
    /**
     * @var string The image for the pawn.
     */
    private $image;
    /**
     * @var int The color of the pawn.
     */
    private $color;
    /**
     * @var int Starting row of the pawn.
     */
    private $startRow;
    /**
     * @var int Starting column of the pawn.
     */
    private $startCol;

    /**
     * Pawn constructor.
     *
     * @param int $color Color of the pawn.
     * @param int $row Starting row of the pawn.
     * @param int $col Starting column of the pawn.
     */
    public function __construct($color, $row, $col) {
        $this->color = $color;
        $this->startRow = $row;
        $this->startCol = $col;
        if ($color == 0) {
            $this->image = 'images/yellow.png';
        }
        if ($color == 1) {
            $this->image = 'images/green.png';
        }
        if ($color == 2) {
            $this->image = 'images/red.png';
        }
        if ($color == 3) {
            $this->image = 'images/blue.png';
        }

    }

    /**
     * Sets the start tile
     *
     * @param int $row Row of the start tile.
     * @param int $col Column of then start tile.
     */
    public function setStartTile($row, $col) {
        $this->startRow = $row;
        $this->startCol = $col;
    }

    /**
     * @return mixed Get the pawn's color.
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @return string Get the pawn's image.
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @return int
     */
    public function getStartRow() {
        return $this->startRow;
    }

    /**
     * @return int
     */
    public function getStartCol() {
        return $this->startCol;
    }

    /**
     * Get whether the pawn is on it's start.
     *
     * @param int $row The current row of the pawn.
     * @param int $col The current column of the pawn.
     *
     * @return bool If the pawn is on it's start.
     */
    public function getOnStart($row, $col) {
        return ($this->startRow == $row && $this->startCol == $col);
    }
}
