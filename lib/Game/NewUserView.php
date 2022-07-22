<?php


namespace Game;


class NewUserView extends View
{
    public function __construct(array $get, array $session) {
        // ?e is set when an error should be displayed
        if (isset($get["e"])) {
            $this->error = true;

            // Get the error message from the session
            if (isset($session[User::NEW_USER_ERROR_MESSAGE])) {
                $this->errorMessage = $session[User::NEW_USER_ERROR_MESSAGE];
            }
        }

        $this->setTitle("Sorry! Game");
        $this->addLink("matchmaking.php", "Home");
        $this->addLink("instruction.php", "Instructions");
    }

    public function present(): string {
        $html = <<<HTML
<div class="main">
<h1>Create An Account</h1>
HTML;

        if ($this->error) {
            $html .= '<p class="error">';
            $html .= $this->errorMessage;
            $html .= '</p>';
        }

        $html .= <<<HTML
<form method="post" action="post/new-user-post.php">
    <p class="player"><label for="email">Email:</label> <input type="email" name="email" id="email"></p>
    <p class="player"><label for="username">Username:</label> <input type="text" name="username" id="username"></p>
    <p class="player"><input type="submit" value="Sign Up" name="Submit" id="newuser"></p>
</form>
</div>
HTML;

        return $html;
    }

    private $error = false; // If an error should be displayed
    private $errorMessage; // The error message to display
}

