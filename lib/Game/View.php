<?php


namespace Game;


class View {

    public function setTitle($title) {
        $this->title = $title;
    }

    public function head() {
        return <<<HTML
<meta charset="utf-8">
<title>$this->title</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="lib/game.css">
HTML;
    }

    public function header() {
        $html = '<header>';
        if(count($this->links) > 0) {
            $html .= "<p>";
            foreach($this->links as $link) {
                $html .= '<a href="' .
                    $link['href'] . '">' .
                    $link['text'] . '</a> ';
            }
            $html .= "</p>";
        }

        $additional = $this->headerAdditional();

        $html .= <<<HTML
<h1>$this->title</h1>
$additional
</header>
HTML;

        return $html;
    }

    public function addLink($href, $text) {
        $this->links[] = ["href" => $href, "text" => $text];
    }

    protected function headerAdditional() {
        return '';
    }

    public function protect($site, $user) {
        if($user !== null) {
            return true;
        }

        $this->protectRedirect = $site->getRoot() . "/";
        return false;
    }

    public function isLobbyRedirect($site, $user){
        $games = new Games($site);
        $game_id = $games->isUserInStatus($user,GAMES::IN_LOBBY);
        if($game_id !== null){
            $this->redirect = $site->getRoot() . "/lobby.php";
            return $game_id;
        }
        $this->redirect = $site->getRoot() . "/matchmaking.php";
        return null;
    }

    public function isGameRedirect($site,$user){
        $games = new Games($site);
        $game_id = $games->isUserInStatus($user,GAMES::GAME_STARTED);
        if($game_id !== null){
            $this->redirect = $site->getRoot() . "/game.php";
            return true;
        }
        return false;
    }

    /**
     * Get any redirect page
     */
    public function getProtectRedirect() {
        return $this->protectRedirect;
    }

    public function getRedirect(){
        return $this->redirect;
    }

    private $redirect = null;
    private $protectRedirect = null;    // Page protection redirect
    private $title = "";	// The page title
    private $links = [];    // Links to add to the nav bar

}
