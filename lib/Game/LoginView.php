<?php


namespace Game;


class LoginView extends View
{

    public function __construct(array &$session, $get, Site $site)
    {
        $this->setTitle("Sorry! Login");

        $root = $site->getRoot();

        $this->addLink("$root/new-user.php", "New User");
        $this->addLink("$root/matchmaking.php", "Lobbies");
    }

    public function present() {
        $html = <<<HTML
<div class="main">
<h1>Login To Play</h1>
<form method="post" action="post/login.php">
    <p class="player"><label for="email">Email</label> <input type="email" id="email" name="email" placeholder="Email"></p>
    <p class="player"><label for="password">Password</label> <input type="password" id="password" name="password" placeholder="Password"></p>
    <p class="player"><input type="submit" value="Log in"></p>
</form>
</div>
HTML;

        // <input type="submit" value="Lost Password">

//        if ($this->error) {
//            $html .= <<<HTML
//<p class="msg">Invalid login credentials</p>
//HTML;
//        }

        return $html;
    }
}
