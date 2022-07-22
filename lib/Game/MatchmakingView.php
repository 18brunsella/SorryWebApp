<?php


namespace Game;


class MatchmakingView extends View {
    public function __construct(Game $game, Site $site) {
        $this->game = $game;
        $this->site = $site;
        $this->setTitle("Matchmaking");
        $this->addLink("instruction.php", "Instructions");
        $this->addLink("post/logout.php", "Log Out");
    }

    public function present(): string {
        $root = $this->site->getRoot();

        $html = <<<HTML
<div class="main">
    <form method="POST" action="$root/post/matchmaking.php">
HTML;

        $games = new Games($this->site);
        $all_games = $games->getGames();

        $avail_games = 0;
        foreach($all_games as $game) {
            if ($game->getStatus() == Games::IN_LOBBY) {
                $avail_games++;
            }
        }

        if ($avail_games === 0) {
            $html .= "<strong>There are no available lobbies.</strong>";

            $html .= "<p class='buttons'>";
        } else {
            $html .= <<<HTML
        <table id="games">
            <thead>
                <th></th>
                <th>Game ID</th>
                <th>Players</th>
            </thead>
HTML;

            foreach ($all_games as $game) {
                if ($game->getStatus() != Games::IN_LOBBY) {
                    continue;
                }

                $id = $game->getId();
                $players = $game->playerCount();
                $disabled = ($players === 4) ? "disabled" : "";

                $html .= <<<HTML
            <tr class="$disabled">
                <td><input type="radio" name="chosenGame" value="$id" $disabled></td>
                <td>$id</td>
                <td>$players</td>
            </tr>
HTML;
            }

            $html .= "</table>";
            $html .= "<p class='buttons'>";
            $html .= '<input type="submit" name="join" value="Join Game" id="joinGame">';
        }

        $html .= <<<HTML
        <input type="submit" name="create" value="New Game" id="createGame"></p>
    </form>
</div>
HTML;

        return $html;
    }

    private $game; // The game object
    private $site;
}
