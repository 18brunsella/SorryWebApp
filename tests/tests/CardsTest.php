<?php


use PHPUnit\Framework\TestCase;

class CardsTest extends TestCase {
    public function testCreate() {
        $cards = new Game\Cards();
        $this->assertInstanceOf("Game\Cards", $cards);

        $this->assertEquals(45, $cards->getCount());

        $deck = $cards->getCards();

        foreach ($deck as $card) {
            $this->assertInstanceOf("Game\Card", $card);
        }
        $this->assertTrue(is_array($deck));
    }
}
