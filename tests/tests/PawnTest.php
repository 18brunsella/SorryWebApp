<?php


use PHPUnit\Framework\TestCase;

class PawnTest extends TestCase {
    public function testCreate() {
        $pawn = new Game\Pawn(3, 4,0);
        $this->assertInstanceOf("Game\Pawn", $pawn);

        $this->assertEquals($pawn->getColor(), 3);
        $this->assertEquals($pawn->getImage(),"images/blue.png");

    }
}