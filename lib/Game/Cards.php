<?php


namespace Game;


/**
 * Class Cards.
 * @package Game
 */
class Cards {
    /**
     * @var array The deck of cards.
     */
    private $cards = [];

    /**
     * Cards constructor.
     * Constructs the deck with the 45 Card objects, and shuffles.
     */
    public function __construct() {
        $cardList = [1, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5,
                     5, 5, 7, 7, 7, 7, 8, 8, 8, 8, 10, 10, 10, 10, 11, 11, 11,
                     11, 12, 12, 12, 12, 'sorry', 'sorry', 'sorry', 'sorry'];

        foreach ($cardList as $i) {
            $card = new Card($i);
            $this->cards[] = $card;
        }

        shuffle($this->cards);
    }

    /**
     * Removes the first card in the deck and returns it.
     * @return card The first card in the deck.
     */
    public function getTop() {
        return array_shift($this->cards);
    }

    /**
     * Returns the deck of Card objects.
     * @return array The deck of cards.
     */
    public function getCards() {
        return $this->cards;
    }

    /**
     * Returns the count of cards still in the deck.
     * @return int The amount of cards still in the deck.
     */
    public function getCount() {
        return count($this->cards);
    }
}
