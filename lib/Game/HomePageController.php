<?php


namespace Game;


/**
 * Class HomePageController
 * @package Game
 */
class HomePageController {
    /**
     * @var Game The Game object.
     */
    private $game;
    /**
     * @var bool If the input is valid.
     */
    private $valid = false;

    /**
     * HomePageController constructor.
     *
     * @param Game $game
     * @param array $post
     */
    public function __construct(Game $game, array $post) {
        $this->game = $game;

        $zero_set = false;
        $one_set = false;
        $two_set = false;
        $three_set = false;

        if (isset($post['playerOne']) && $post['playerOne'] != "") {
            $zero_set = true;
        }
        if (isset($post['playerTwo']) && $post['playerTwo'] != "") {
            $one_set = true;
        }
        if (isset($post['playerThree']) && $post['playerThree'] != "") {
            $two_set = true;
        }
        if (isset($post['playerFour']) && $post['playerFour'] != '') {
            $three_set = true;
        }

        $this->verifySubmission($zero_set, $one_set, $two_set, $three_set);
        $game->initializePlayerPawns();
    }

    /**
     * Verify submission and add players.
     *
     * @param bool $zero If there is a player zero.
     * @param bool $one If there is a player one.
     * @param bool $two If there is a player two.
     * @param bool $three If there is a player three.
     */
    private function verifySubmission($zero, $one, $two, $three) {
        if ($zero && !$one && !$two && !$three) {
            $this->game->addPlayer(0);
            $this->valid = true;
        } else if ($zero && $one && !$two && !$three) {
            $this->game->addPlayer(0);
            $this->game->addPlayer(1);
            $this->valid = true;
        } else if ($zero && $one && $two && !$three) {
            $this->game->addPlayer(0);
            $this->game->addPlayer(1);
            $this->game->addPlayer(2);
            $this->valid = true;
        } else if ($zero && $one && $two && $three) {
            $this->game->addPlayer(0);
            $this->game->addPlayer(1);
            $this->game->addPlayer(2);
            $this->game->addPlayer(3);
            $this->valid = true;
        } else {
            $this->valid = false;
        }
    }

    /**
     * @return bool Get whether the input is valid.
     */
    public function isValid(): bool {
        return $this->valid;
    }
}
