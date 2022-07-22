<?php


use PHPUnit\Framework\TestCase;

class CardTest extends TestCase {
    public function testCreate() {
        $card = new Game\Card(1);
        $this->assertInstanceOf("Game\Card", $card);
        $this->assertEquals('images/card_1.png', $card->getImage());
        $this->assertEquals(1, $card->getDescription());

        $sorry = new Game\Card('sorry');
        $this->assertInstanceOf("Game\Card", $sorry);
        $this->assertEquals('images/card_sorry.png', $sorry->getImage());
        $this->assertEquals(0, $sorry->getDescription());
    }

}