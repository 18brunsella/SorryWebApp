<?php


use Game\Game;
use Game\HomePageView;
use PHPUnit\Framework\TestCase;

class HomePageViewTest extends TestCase {
    public function test_display_home_page() {
        $view = new HomePageView(new Game());

        $expectedHTML = <<<HTML
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

        $this->assertContains($expectedHTML, $view->present());
    }
}
