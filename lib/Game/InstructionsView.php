<?php


namespace Game;


class InstructionsView extends View
{
    public function __construct(Game $game) {
        $this->game = $game;
        $this->setTitle("Sorry! Instructions");
        $this->addLink("matchmaking.php", "Home");
        $this->addLink("new-user.php", "New User");
    }

    public function present() {
        return <<<HTML
<div class="main">
    <h1>How To Join A Game</h1>

    <p class = "instruct">After you logged in you were redirected to the Matchmaking page. Here you see a table that shows any available Sorry! games for you to join! You can select one of
    these and click 'Join Game' or you can create a 'New Game'. You will then be directed to a lobby where you will see any other players who have joined that game. The player who created
    the game can choose to 'Begin Game' when they are ready, whether there are 1-4 players in the game! Once the game is started you will be able to see the moves that the other players are
    making, and will be able to make your own moves when it is your turn!
    </p>

    <h1>How To Play</h1>

    <p class = "instruct">The goal of the game is to get all four of your pawns from the start space to the home space. Pawns are moved through the cards placed in the middle of the board.
        One player is selected to play first. To begin the game, you would need to move one of the four pawns out of the start space. A player can move a pawn of the start space only if they
        a 1 or 2 card. A 1 or 2 card will only move the pawn out of the start space, the 2 card does not mean they can move 2 spaces out of the start space.
    </p>

    <p class = "instruct">A pawn can jump over any other pawns on the board during their move. But two pawns cannot be on the same tile. The pawn that lands on the tile that was already occupied will bump that
        pawn back to the player's start space. Players cannot bump their own pawn out of the tile. If the only move is to do that, then the player's pawn would remain in place and the player
        loses their turn.
    </p>

    <p class = "instruct">If a pawn lands at the start of a slide tile of a color that is not their own, it immediately slides to the last square of the slide. If there are any pawns in between the slide tiles
        (including the pawns owned by the slide player), it will get bumped off the tile and sent back to the player's start space.
    </p>

    <p class = "instruct"> The last five squares before each player's home space is a safety zone, where only the pawns corresponding the color can access this zone. Pawns within this zone cannot be bumped by
        opposing pawns or cannot be switched through a Sorry! or 11 card. A 10 or 4 card are cards that move a pawn backward. These cards can move pawns out of the safety space, which now means
        they are no longer considered in the safe zone.
    </p>

    <h1>Team 6</h1>
    <p>Arvid Tatsuya Brunsell</p>
    <p>Owen D'Aprile</p>
    <p>Sydney Kay Hickmott</p>
    <p>Diego Marzejon</p>
    <p>Jakob Louis Therkelsen</p>
    <br>
</div>
HTML;
    }

    private $game; // The game object
}
