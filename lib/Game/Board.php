<?php


namespace Game;


/**
 * Class Board.
 * @package Game
 */
class Board {
    /**
     * Yellow player.
     */
    const YELLOW = 0;
    /**
     * Green player.
     */
    const GREEN = 1;
    /**
     * Red player.
     */
    const RED = 2;
    /**
     * Blue player.
     */
    const BLUE = 3;

    /**
     * @var array[] 2D array for connecting tiles together.
     */
    private $tiles = [[]];
    /**
     * @var array Array for slide tiles.
     */
    private $slideLocations = [];
    /**
     * @var array Array for start tiles.
     */
    private $startSpaces = [];
    /**
     * @var array Array for home tiles.
     */
    private $homeSpaces = [];

    /**
     * Board constructor.
     */
    public function __construct() {
        $this->constructTiles();
        $this->setSlideTiles();
    }

    /**
     * Construct initial tiles and their adjacent tiles.
     */
    public function constructTiles() {
        // Build empty tiles
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $this->tiles[$i][$j] = 1;
            }
        }
        // Top side
        for ($j = 0; $j < 16; $j++) {
            $this->tiles[0][$j] = new Tile(0, $j);
            if ($j != 0) { // Create the adjacent tiles
                $this->buildGraphDirection(0, $j - 1, 0, $j);
            }
            if ($j == 2) { // Yellow home
                for ($i = 1; $i < 6; $i++) {
                    $this->tiles[$i][2] = new Tile($i, 2, Tile::SAFE, 0);
                    $this->buildGraphDirection($i - 1, 2, $i, 2);
                }
            }
        }
        // Right side
        for ($i = 0; $i < 16; $i++) {
            if ($i != 0) {
                $this->tiles[$i][15] = new Tile($i, 15);
                $this->buildGraphDirection($i - 1, 15, $i, 15);
            }
            if ($i == 2) { // Green home
                for ($j = 14; $j > 9; $j--) {
                    $this->tiles[2][$j] = new Tile(2, $j, Tile::SAFE, 1);
                    $this->buildGraphDirection(2, $j + 1, 2, $j);
                }
            }
        }
        // Bottom side
        for ($j = 15; $j > -1; $j--) {
            if ($j != 15) {
                $this->tiles[15][$j] = new Tile(15, $j);
                $this->buildGraphDirection(15, $j + 1, 15, $j);
            }
            if ($j == 13) { // Red home
                for ($i = 14; $i > 9; $i--) {
                    $this->tiles[$i][13] = new Tile($i, 13, Tile::SAFE, 2);
                    $this->buildGraphDirection($i + 1, 13, $i, 13);
                }
            }
        }
        // Left side
        for ($i = 15; $i > -1; $i--) {
            if ($i == 0) {
                $this->buildGraphDirection($i + 1, 0, $i, 0);
            }
            if ($i != 15 && $i != 0) {
                $this->tiles[$i][0] = new Tile($i, 0);
                $this->buildGraphDirection($i + 1, 0, $i, 0);
            }
            if ($i == 13) { // Blue home
                for ($j = 1; $j < 6; $j++) {
                    $this->tiles[13][$j] = new Tile(13, $j, Tile::SAFE, 3);
                    $this->buildGraphDirection(13, $j - 1, 13, $j);
                }
            }
        }
    }

    /**
     * Builds the nodes in a two way direction. Uses two arrays to chart
     * forward and backward direction.
     *
     * @param int $row_a Row of 1.
     * @param int $col_a Column of 1.
     * @param int $row_b Row of 2.
     * @param int $col_b Column of 2.
     */
    private function buildGraphDirection($row_a, $col_a, $row_b, $col_b) {
        $node_a = $this->tiles[$row_a][$col_a];
        $node_b = $this->tiles[$row_b][$col_b];
        $node_a->addToForward($node_b);
        $node_b->addToBackward($node_a);
    }

    /**
     * Builds the slide tiles.
     */
    private function setSlideTiles() {
        // Top Slides (Yellow)
        $tile1 = $this->tiles[0][1];
        $tile2 = $this->tiles[0][2];
        $tile3 = $this->tiles[0][3];
        $tile4 = $this->tiles[0][4];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4];

        $tile1 = $this->tiles[0][9];
        $tile2 = $this->tiles[0][10];
        $tile3 = $this->tiles[0][11];
        $tile4 = $this->tiles[0][12];
        $tile5 = $this->tiles[0][13];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4, $tile5];

        // Right Slides (Green)
        $tile1 = $this->tiles[1][15];
        $tile2 = $this->tiles[2][15];
        $tile3 = $this->tiles[3][15];
        $tile4 = $this->tiles[4][15];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4];

        $tile1 = $this->tiles[9][15];
        $tile2 = $this->tiles[10][15];
        $tile3 = $this->tiles[11][15];
        $tile4 = $this->tiles[12][15];
        $tile5 = $this->tiles[13][15];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4, $tile5];

        // Bottom Slides (Red)
        $tile1 = $this->tiles[15][14];
        $tile2 = $this->tiles[15][13];
        $tile3 = $this->tiles[15][12];
        $tile4 = $this->tiles[15][11];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4];

        $tile1 = $this->tiles[15][6];
        $tile2 = $this->tiles[15][5];
        $tile3 = $this->tiles[15][4];
        $tile4 = $this->tiles[15][3];
        $tile5 = $this->tiles[15][2];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4, $tile5];

        // Left Slides (Blue)
        $tile1 = $this->tiles[14][0];
        $tile2 = $this->tiles[13][0];
        $tile3 = $this->tiles[12][0];
        $tile4 = $this->tiles[11][0];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4];

        $tile1 = $this->tiles[6][0];
        $tile2 = $this->tiles[5][0];
        $tile3 = $this->tiles[4][0];
        $tile4 = $this->tiles[3][0];
        $tile5 = $this->tiles[2][0];
        $this->slideLocations[] = [$tile1, $tile2, $tile3, $tile4, $tile5];

    }

    /**
     * Builds home tiles.
     *
     * @param int $color the color being used
     */
    public function buildHome($color) {
        if ($color == self::YELLOW) {
            $this->buildHomeSpace(6, 1, self::YELLOW);
            $this->buildHomeSpace(6, 3, self::YELLOW);
            $this->buildHomeSpace(8, 1, self::YELLOW);
            $this->buildHomeSpace(8, 3, self::YELLOW);
        } else if ($color == self::GREEN) {
            $this->buildHomeSpace(1, 7, self::GREEN);
            $this->buildHomeSpace(1, 9, self::GREEN);
            $this->buildHomeSpace(3, 7, self::GREEN);
            $this->buildHomeSpace(3, 9, self::GREEN);
        } else if ($color == self::RED) {
            $this->buildHomeSpace(7, 14, self::RED);
            $this->buildHomeSpace(9, 14, self::RED);
            $this->buildHomeSpace(7, 12, self::RED);
            $this->buildHomeSpace(9, 12, self::RED);
        } else if ($color == self::BLUE) {
            $this->buildHomeSpace(12, 6, self::BLUE);
            $this->buildHomeSpace(12, 8, self::BLUE);
            $this->buildHomeSpace(14, 6, self::BLUE);
            $this->buildHomeSpace(14, 8, self::BLUE);
        }
    }

    /**
     * Builds the home spaces for the pawns.
     *
     * @param int $tr The row.
     * @param int $tc The column.
     * @param int $color Color of the start space.
     */
    private function buildHomeSpace($tr, $tc, $color) {
        $er = 0;
        $ec = 0;
        if ($color == self::YELLOW) {
            $er = 5;
            $ec = 2;
        } else if ($color == self::GREEN) {
            $er = 2;
            $ec = 10;
        } else if ($color == self::RED) {
            $er = 10;
            $ec = 13;
        } else if ($color == self::BLUE) {
            $er = 13;
            $ec = 5;
        }

        $this->tiles[$tr][$tc] = new Tile($tr, $tc, Tile::HOME);
        $this->tiles[$er][$ec]->addToForward($this->tiles[$tr][$tc]);
        $this->homeSpaces[$color][] = $this->tiles[$tr][$tc];
    }

    /**
     * Build the starting area for a player.
     *
     * @param int $color A player color.
     */
    public function buildStart($color) {
        if ($color == self::YELLOW) {
            $this->buildStartSpace(1, 4, self::YELLOW);
            $this->buildStartSpace(2, 3, self::YELLOW);
            $this->buildStartSpace(3, 4, self::YELLOW);
            $this->buildStartSpace(2, 5, self::YELLOW);
        } else if ($color == self::GREEN) {
            $this->buildStartSpace(3, 13, self::GREEN);
            $this->buildStartSpace(4, 12, self::GREEN);
            $this->buildStartSpace(4, 14, self::GREEN);
            $this->buildStartSpace(5, 13, self::GREEN);
        } else if ($color == self::RED) {
            $this->buildStartSpace(12, 11, self::RED);
            $this->buildStartSpace(13, 10, self::RED);
            $this->buildStartSpace(14, 11, self::RED);
            $this->buildStartSpace(13, 12, self::RED);
        } else if ($color == self::BLUE) {
            $this->buildStartSpace(12, 2, self::BLUE);
            $this->buildStartSpace(11, 1, self::BLUE);
            $this->buildStartSpace(11, 3, self::BLUE);
            $this->buildStartSpace(10, 2, self::BLUE);
        }
    }

    /**
     * Builds the start spaces for the pawns.
     *
     * @param int $tr The row.
     * @param int $tc The column.
     * @param int $color Color of the start space.
     */
    private function buildStartSpace($tr, $tc, $color) {
        $er = 0;
        $ec = 0;
        if ($color == self::YELLOW) {
            $er = 0;
            $ec = 4;
        } else if ($color == self::GREEN) {
            $er = 4;
            $ec = 15;
        } else if ($color == self::RED) {
            $er = 15;
            $ec = 11;
        } else if ($color == self::BLUE) {
            $er = 11;
            $ec = 0;
        }

        $this->tiles[$tr][$tc] = new Tile($tr, $tc, Tile::START);
        $this->tiles[$tr][$tc]->addToForward($this->tiles[$er][$ec]);
        $this->startSpaces[$color][] = $this->tiles[$tr][$tc];
    }

    /**
     * Get the tiles.
     *
     * @return array[] The array of tiles.
     */
    public function getTiles() {
        return $this->tiles;
    }

    /**
     * Get a tile.
     *
     * @param int $row A row.
     * @param int $col A column.
     *
     * @return mixed The tile at $row, $col.
     */
    public function getTile($row, $col) {
        return $this->tiles[$row][$col];
    }

    /**
     * Get the locations of slide tiles.
     *
     * @return array The locations of slide tiles.
     */
    public function getSlideLocations() {
        return $this->slideLocations;
    }

    public function getStartSpaces($color, $ndx) {
        return $this->startSpaces[$color][$ndx];
    }

    public function getHomeSpaces($color, $ndx) {
        return $this->homeSpaces[$color][$ndx];
    }
}
