<?php


namespace Game;


/**
 * View class for generating the home and instructions pages.
 * @package Game
 */
class HomePageView extends View {
    /**
     * HomePageView constructor.
     *
     * @param Game $game The game object.
     */
    public function __construct(Game $game) {
        $this->game = $game;
        $this->setTitle("Sorry!");
        $this->addLink("instruction.php", "Instructions");
        $this->addLink("new-user.php", "New User");
        $this->addLink("login.php", "Login");
    }

    public function present() {
        return <<<HTML
<div class="main">
<h1>Welcome To Sorry!</h1>
<form method="post" action="post/home-post.php">
    <h2>Enter Player Names</h2>
    <p class="player"><label for="playerOne">Yellow:</label> <input type="text" name="playerOne" id="playerOne"></p>
    <p class="player"><label for="playerTwo">Green:</label> <input type="text" name="playerTwo" id="playerTwo"></p>
    <p class="player"><label for="playerThree">Red:</label> <input type="text" name="playerThree" id="playerThree"></p>
    <p class="player"><label for="playerFour">Blue:</label> <input type="text" name="playerFour" id="playerFour"></p>
    <p class="player"><input type="submit" value="Play Game" name="Submit" id="Submit"></p>
</form>
</div>
HTML;
    }

    private $game; // The game object
}
