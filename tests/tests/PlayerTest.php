<?php


use Game\Pawn;
use Game\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase {
    public function testGetPawn() {
        $player = new Player(0);
        $pawn = new Pawn(0, 0, 0);

        $player->addPawn($pawn);

        $this->assertEquals($pawn, $player->getPawn(0));
    }
}