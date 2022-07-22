<?php


use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase {

    public function testConstructTiles() {
        $board = new Game\Board();
        $tiles = $board->getTiles();
        //test the home entrances
        $yellow_home = $tiles[1][2];
        //echo $yellow_home->getEdgesBackward()[0]->getRow();
        $this->assertContains($tiles[0][2], $yellow_home->getEdgesBackward());
        $this->assertContains($tiles[2][2], $yellow_home->getEdgesForward());
        $green_home = $tiles[2][14];
        $this->assertContains($tiles[2][15], $green_home->getEdgesBackward());
        $this->assertContains($tiles[2][13], $green_home->getEdgesForward());
        $red_home = $tiles[14][13];
        $this->assertContains($tiles[15][13], $red_home->getEdgesBackward());
        $this->assertContains($tiles[13][13], $red_home->getEdgesForward());
        $blue_home = $tiles[13][1];
        $this->assertContains($tiles[13][0], $blue_home->getEdgesBackward());
        $this->assertContains($tiles[13][2], $blue_home->getEdgesForward());

        //test corners for connection
        $top_left = $tiles[0][0];
        $this->assertContains($tiles[0][1], $top_left->getEdgesForward());
        $this->assertContains($tiles[1][0], $top_left->getEdgesBackward());
        $top_right = $tiles[0][15];
        $this->assertContains($tiles[0][14], $top_right->getEdgesBackward());
        $this->assertContains($tiles[1][15], $top_right->getEdgesForward());
        $bottom_right = $tiles[15][15];
        $this->assertContains($tiles[14][15], $bottom_right->getEdgesBackward());
        $this->assertContains($tiles[15][14], $bottom_right->getEdgesForward());
        $bottom_left = $tiles[15][0];
        $this->assertContains($tiles[14][0], $bottom_left->getEdgesForward());
        $this->assertContains($tiles[15][1], $bottom_left->getEdgesBackward());

    }

    public function testBuildHome() {
        $board = new Game\Board();
        $tiles = $board->getTiles();

        $board->buildHome(0);
        $this->assertNotNull(0, $tiles[6][1]);
        $this->assertNotNull(0, $tiles[6][3]);
        $this->assertNotNull(0, $tiles[8][1]);
        $this->assertNotNull(0, $tiles[8][3]);
        $board->buildHome(1);
        $this->assertNotNull(0, $tiles[1][7]);
        $this->assertNotNull(0, $tiles[1][9]);
        $this->assertNotNull(0, $tiles[3][7]);
        $this->assertNotNull(0, $tiles[3][9]);
        $board->buildHome(2);
        $this->assertNotNull(0, $tiles[7][14]);
        $this->assertNotNull(0, $tiles[9][14]);
        $this->assertNotNull(0, $tiles[7][12]);
        $this->assertNotNull(0, $tiles[9][12]);
        $board->buildHome(3);
        $this->assertNotNull(0, $tiles[12][6]);
        $this->assertNotNull(0, $tiles[12][8]);
        $this->assertNotNull(0, $tiles[14][6]);
        $this->assertNotNull(0, $tiles[14][8]);
    }

    public function testBuildStart() {
        $board = new Game\Board();
        $tiles = $board->getTiles();

        $board->buildHome(0);
        $this->assertNotNull(0, $tiles[1][4]);
        $this->assertNotNull(0, $tiles[2][3]);
        $this->assertNotNull(0, $tiles[3][4]);
        $this->assertNotNull(0, $tiles[2][5]);
        $board->buildHome(1);
        $this->assertNotNull(0, $tiles[3][13]);
        $this->assertNotNull(0, $tiles[4][12]);
        $this->assertNotNull(0, $tiles[4][14]);
        $this->assertNotNull(0, $tiles[5][13]);
        $board->buildHome(2);
        $this->assertNotNull(0, $tiles[12][11]);
        $this->assertNotNull(0, $tiles[13][10]);
        $this->assertNotNull(0, $tiles[14][11]);
        $this->assertNotNull(0, $tiles[13][12]);
        $board->buildHome(3);
        $this->assertNotNull(0, $tiles[12][2]);
        $this->assertNotNull(0, $tiles[11][1]);
        $this->assertNotNull(0, $tiles[11][3]);
        $this->assertNotNull(0, $tiles[10][2]);
    }

    public function testGettersSetters() {
        $board = new Game\Board();
        $this->assertInstanceOf("Game\Tile", $board->getTiles()[0][0]);
        $this->assertInstanceOf("Game\Tile", $board->getTile(0, 0));
        $this->assertNotNull($board->getSlideLocations());
    }
}