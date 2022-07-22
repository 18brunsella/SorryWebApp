<?php


use Game\Board;
use Game\Pawn;
use Game\Tile as Tile;
use PHPUnit\Framework\TestCase;


class TileTest extends TestCase {

    public function testSettersGetters() {
        $tile_a = new Tile(0, 1);
        $pawn = new Pawn(1, 0, 0);

        $this->assertEquals(0, $tile_a->getRow());
        $this->assertEquals(1, $tile_a->getCol());
        $this->assertEquals([], $tile_a->getEdgesForward());
        $this->assertEquals([], $tile_a->getEdgesBackward());

        $this->assertFalse($tile_a->getBlocked());
        $tile_a->setBlocked(true);
        $this->assertTrue($tile_a->getBlocked());

        $this->assertFalse($tile_a->getReachable());
        $tile_a->setReachable(true);
        $this->assertTrue($tile_a->getReachable());

        $this->assertFalse($tile_a->getOnPath());
        $tile_a->setOnPath(true);
        $this->assertTrue($tile_a->getOnPath());

        $this->assertNull($tile_a->getContains());
        $tile_a->setContains($pawn);
        $this->assertEquals($pawn, $tile_a->getContains());
    }

    public function testAddTo() {
        $tile_a = new Tile(0, 0);
        $tile_b = new Tile(0, 1);

        $tile_a->addToForward($tile_b);
        $tile_b->addToBackward($tile_a);
        $this->assertContains($tile_b, $tile_a->getEdgesForward());
        $this->assertContains($tile_a, $tile_b->getEdgesBackward());
    }

    public function testReachable() {
        $board = new Board();
        $tile = $board->getTile(0, 0);
        $tile->searchReachable(1);
        $tile_to = $board->getTile(0, 1);
        $this->assertTrue($tile_to->getReachable());

        $tile = $board->getTile(0, 6);
        $tile->searchReachable(5);
        $tile_to = $board->getTile(0, 11);
        $this->assertTrue($tile_to->getReachable());

        // corner case - NOT WORKING
        $tile = $board->getTile(0, 14);
        $tile->searchReachable(5);
        $tile_to = $board->getTile(4, 15);
        $this->assertTrue($tile_to->getReachable());
    }

    public function testDistance() {
        $this->assertEquals(
            2, Tile::distance(new Tile(0, 0), new Tile(0, 2)),
            "Tiles on same row, one on start, later tile second"
        );
        $this->assertEquals(
            2, Tile::distance(new Tile(0, 2), new Tile(0, 0)),
            "Tiles on same row, one on start, later tile first"
        );
        $this->assertEquals(
            4, Tile::distance(new Tile(0, 4), new Tile(0, 8)),
            "Tiles on same row, neither on start, later tile second"
        );
        $this->assertEquals(
            4, Tile::distance(new Tile(0, 8), new Tile(0, 4)),
            "Tiles on same row, neither on start, later tile first"
        );
        $this->assertEquals(
            15, Tile::distance(new Tile(0, 0), new Tile(0, 15)),
            "Tiles at opposite ends of top row"
        );
        $this->assertEquals(
            5, Tile::distance(new Tile(0, 13), new Tile(3, 15)),
            "Around top right corner"
        );
        $this->assertEquals(
            5, Tile::distance(new Tile(3, 15), new Tile(0, 13)),
            "Around top right corner, further tile first"
        );
        $this->assertEquals(
            6, Tile::distance(new Tile(8, 15), new Tile(14, 15)),
            "Down right row"
        );
        $this->assertEquals(
            7, Tile::distance(new Tile(13, 15), new Tile(15, 10)),
            "Around bottom right corner"
        );
        $this->assertEquals(
            7, Tile::distance(new Tile(15, 10), new Tile(13, 15)),
            "Around bottom right corner, further tile first"
        );
        $this->assertEquals(
            12, Tile::distance(new Tile(15, 14), new Tile(15, 2)),
            "Along bottom"
        );
        $this->assertEquals(
            7, Tile::distance(new Tile(15, 2), new Tile(10, 0)),
            "Around bottom left corner"
        );
    }

}