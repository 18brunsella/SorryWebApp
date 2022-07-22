<?php


namespace Game;


class Card {
    /**
     * @var mixed The description of the card.
     */
    private $description;
    /**
     * @var string The string for the card image file.
     */
    private $image;

    /**
     * Card constructor.
     * Constructs a card based off the given card number/type (1, 2, sorry, etc.)
     *
     * @param mixed $desc The description of the card.
     */
    public function __construct($desc) {
        $this->description = $desc;
        $this->image = 'images/card_' . strval($desc) . '.png';
    }

    /**
     * Returns the card number/type (1, 2, sorry, etc.).
     * @return int Card description.
     */
    public function getDescription() {
        if ($this->description == 'sorry') {
            return 0;
        }
        return $this->description;
    }

    /**
     * Returns the string of the card image
     * @return string Card image string.
     */
    public function getImage() {
        return $this->image;
    }
}
