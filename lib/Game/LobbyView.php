<?php


namespace Game;


class LobbyView extends View
{
    public function __construct(Site $site, Game $game, User $user) {
        $this->site = $site;
        $this->game = $game;
        $this->game_id = $user->getGameKey();
        $this->setTitle("Sorry! Lobby");
        $this->addLink("index.php", "Home");
        // $this->addLink("matchmaking.php", "Matchmaking");
        $this->addLink("instruction.php", "Instructions");
        $this->addLink("post/logout.php", "Log Out");
        $this->addLink("post/backout.php", "Back Out");
        $games = new Games($this->site);
        $this->createGame($games->get($user->getGameKey())[0]);
        $this->host = $games->isPlayerHost($user);
    }

    public function present() {

        $games = new Games($this->site);
        $playersInGame = $games->get($this->game_id);
        $users = new Users($this->site);

        $html = <<<HTML
<div class="main">
    <h1>Waiting For Others To Join</h1>
HTML;

        $player0 = $users->get($playersInGame[0]['player0']);
        $p0_username = "";
        if($player0 != null){
            $p0_username = $player0->getUsername();
            $html .= '<img src = "images/yellow.png" height="100px" width="100px">';
        }else{
            $html .= '<img class = "lobbypawns" src = "images/yellow.png" height="100px" width="100px">';
        }

        $player1 = $users->get($playersInGame[0]['player1']);
        $p1_username = "";
        if($player1 != null){
            $p1_username = $player1->getUsername();
            $html .= '<img src = "images/green.png" height="100px" width="100px">';
        }else{
            $html .= '<img class = "lobbypawns" src = "images/green.png" height="100px" width="100px">';
        }

        $player2 = $users->get($playersInGame[0]['player2']);
        $p2_username = "";
        if($player2 != null){
            $p2_username = $player2->getUsername();
            $html .= '<img src = "images/red.png" height="100px" width="100px">';
        }else{
            $html .= '<img class = "lobbypawns" src = "images/red.png" height="100px" width="100px">';
        }

        $player3 = $users->get($playersInGame[0]['player3']);
        $p3_username = "";
        if($player3 != null){
            $p3_username = $player3->getUsername();
            $html .= '<img src = "images/blue.png" height="100px" width="100px">';
        }else{
            $html .= '<img class = "lobbypawns" src = "images/blue.png" height="100px" width="100px">';
        }

        $html .= '<div class = "playersInLobby">';
        $counter = 1;
        if($p0_username != ""){
            $html .= "<p> Player $counter: " . $p0_username . "</p>";
            $counter++;
        }
        if($p1_username != ""){
            $html .= "<p> Player $counter: " . $p1_username . "</p>";
            $counter++;
        }
        if($p2_username != ""){
            $html .= "<p> Player $counter: " . $p2_username . "</p>";
            $counter++;
        }
        if($p3_username != ""){
            $html .= "<p> Player $counter: " . $p3_username . "</p>";
            $counter++;
        }

        if ($this->host) {
            $html .= <<<HTML
</div>
    <form method="post" action="post/lobby.php">
        <p class="player"><input type="submit" value="Begin Game" name="Submit" id="begingame"></p>
        <p class="player"><input type="submit" value="Delete Game" name="Delete" id="deletegame"></p>
    </form>
</div>
HTML;
        }

        return $html;
    }

    public function createGame($players){
        $this->game->newGame();
        $this->game->addPlayer(0);

        if($players['player1'] !== null){
            $this->game->addPlayer(1);
        }
        if($players['player2'] !== null){
            $this->game->addPlayer(2);
        }
        if($players['player3'] !== null){
            $this->game->addPlayer(3);
        }
        $this->game->initializePlayerPawns();

    }

    private $game; // The game object
    private $site;
    private $game_id;
    private $host; // Is player the host?
}
