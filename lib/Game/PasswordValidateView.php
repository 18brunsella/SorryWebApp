<?php


namespace Game;

class PasswordValidateView extends View{
    const INVALID = 1;
    const NO_USER_EXISTS = 2;
    const NO_VALID_MATCH = 3;
    const NO_PASSWORD_MATCH = 4;
    const PASSWORD_TOO_SHORT = 5;


    public function __construct(Site $site, $get) {
        $this->site = $site;
        if(isset($get['v'])) {
            $this->validator = strip_tags($get['v']);
        }
        if(isset($get['e'])) {
            $this->error = strip_tags($get['e']);
        }

        $this->setTitle("Account Validation");
        $this->addLink("index.php", "Home");
    }

    public function present()
    {
        $html = <<<HTML
<div class="main">
<h1>Enter Your Email And Password</h1>
<form method="post" action="post/password-validate.php">
    <input type="hidden" name="validator" value="$this->validator">
        <p class = "player"><label for="email">Email</label> <input type="email" id="email" name="email" placeholder="Email"></p>
        <p class = "player"><label for="password">Password</label> <input type="password" id="password" name="password" placeholder="Password"></p>
        <p class = "player"><label for="password">Password (again):</label> <input type="password" id="password2" name="password2" placeholder="Password"></p>
		<p class = "player"><input type="submit" name="ok" id="ok" value="OK"> <input type="submit" name="cancel" id="cancel" value="Cancel"></p>
</form>
</div>
HTML;
        if($this->error == self::INVALID){
            $html.= '<p class="msg">Invalid or unavailable validator</p>';
        }
        elseif($this->error == self::NO_USER_EXISTS){
            $html.= '<p class="msg">Email address is not for a valid user</p>';
        }
        elseif($this->error == self::NO_VALID_MATCH){
            $html.= '<p class="msg">Email address does not match validator</p>';
        }
        elseif($this->error == self::NO_PASSWORD_MATCH){
            $html.= '<p class="msg">Passwords did not match</p>';
        }
        elseif($this->error == self::PASSWORD_TOO_SHORT){
            $html.= '<p class="msg">Password too short</p>';
        }
        $html.='</form>';

        return $html;
    }

    private $error;
    private $site;	///< The Site object
    private $validator;
}
