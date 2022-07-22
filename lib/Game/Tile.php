<?php


namespace Game;


/**
 * Class Tile
 * @package Game
 */
class Tile {
    /**
     * Yellow player color.
     */
    const YELLOW = 0;
    /**
     * Green player color.
     */
    const GREEN = 1;
    /**
     * Red player color.
     */
    const RED = 2;
    /**
     * Blue player color.
     */
    const BLUE = 3;

    /**
     * Normal tile.
     */
    const NORM = 10;
    /**
     * Home tile.
     */
    const HOME = 11;
    /**
     * Start tile.
     */
    const START = 12;
    /**
     * Safe tile.
     */
    const SAFE = 13;

    /**
     * @var int The tile's row.
     */
    private $row;
    /**
     * @var int The tile's column.
     */
    private $col;
    /**
     * @var Pawn|null What pawn is on the tile, if any.
     */
    private $contains = null;
    /**
     * @var array Adjacent tiles forward.
     */
    private $edges_forward = [];
    /**
     * @var array Adjacent tiles backward.
     */
    private $edges_backward = [];
    /**
     * @var bool If the tile is safe.
     */
    private $safe = false;
    /**
     * @var int The type of the tile.
     */
    private $type;
    /**
     * @var int|mixed The safe color.
     */
    private $safe_color;
    /**
     * @var bool If the tile is blocked and cannot be visited.
     */
    private $blocked = false;
    /**
     * @var bool If the tile is on a current path.
     */
    private $onPath = false;
    /**
     * @var bool If the tile is reachable in the current move.
     */
    private $reachable = false;

    /**
     * Tile constructor.
     *
     * @param int $row Row of the board.
     * @param int $col Column of the board.
     * @param int $type The type of the tile.
     * @param int $color The color of the tile.
     */
    public function __construct($row, $col, $type = self::NORM, $color = -1) {
        $this->row = $row;
        $this->col = $col;
        $this->type = $type;
        $this->safe_color = $color;
        $pair = [$row, $col];
        $safe = [[1, 2], [2, 2], [3, 2], [4, 2], [5, 2], [2, 10], [2, 11], [2, 12], [2, 13], [2, 14], [10, 13], [11, 13], [12, 13], [13, 13], [14, 13], [13, 1], [13, 2], [13, 3], [13, 4], [13, 5]];
        $home = [[6, 1], [6, 3], [8, 1], [8, 3], [1, 7], [1, 9], [3, 7], [3, 9], [7, 14], [9, 14], [7, 12], [9, 12], [12, 6], [12, 8], [14, 6], [14, 8]];
        $start = [[1, 4], [2, 3], [3, 4], [2, 5], [3, 13], [4, 12], [4, 14], [5, 13], [12, 11], [13, 10], [14, 11], [13, 12], [12, 2], [11, 1], [11, 3], [10, 2]];
        if (in_array($pair, $safe)) {
            $this->safe = true;
            $this->type = self::SAFE;
        }
        if (in_array($pair, $home)) {
            $this->type = self::HOME;
        }
        if (in_array($pair, $start)) {
            $this->type = self::START;
        }
    }

    /**
     * Determine the distance between 2 tiles
     *
     * @param Tile $tile1
     * @param Tile $tile2
     *
     * @return int
     */
    public static function distance(Tile $tile1, Tile $tile2): int {
        return abs(($tile2->row - $tile1->row)) + abs(($tile2->col - $tile1->col));
    }

    /**
     * Finds reachable tiles.
     *
     * @param int $distance Allowed distance from this tile.
     * @param bool $isForward If the search should go forward.
     */
    public function searchReachable($distance, $isForward = true) {
        // The path is done if it at the end of the distance
        if ($distance === 0) {
            $this->reachable = true;
            return;
        }
        $traverse_edge = $this->edges_forward;
        if (!$isForward) {
            $traverse_edge = $this->edges_backward;
        }

        // Replaced to with edges
        foreach ($traverse_edge as $to) {
            if (!$to->blocked && !$to->onPath) {
                $to->searchReachable($distance - 1, $isForward);
            }
        }

        $this->onPath = false;
    }

    public function addToForward($to) {
        $this->edges_forward[] = $to;
    }

    public function addToBackward($to) {
        $this->edges_backward[] = $to;
    }

    public function getSafeColor() {
        return $this->safe_color;
    }

    public function getRow() {
        return $this->row;
    }

    public function getCol() {
        return $this->col;
    }

    public function getEdgesForward() {
        return $this->edges_forward;
    }

    public function getEdgesBackward() {
        return $this->edges_backward;
    }

    public function getBlocked() {
        return $this->blocked;
    }

    public function setBlocked($bool) {
        $this->blocked = $bool;
    }

    public function getOnPath() {
        return $this->onPath;
    }

    public function setOnPath($bool) {
        $this->onPath = $bool;
    }

    public function getReachable() {
        return $this->reachable;
    }

    public function setReachable($bool) {
        $this->reachable = $bool;
    }

    public function getContains() {
        return $this->contains;
    }

    public function setContains($pawn) {
        $this->contains = $pawn;
    }

    public function getSafe() {
        return $this->safe;
    }

    public function getType() {
        return $this->type;
    }
}
