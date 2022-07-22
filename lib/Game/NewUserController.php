<?php


namespace Game;


/**
 * Controller for the new user page
 * @package Game
 */
class NewUserController{
    /**
     * NewUserController constructor
     *
     * Handles adding the actual user
     *
     * @param Site $site The global site object
     * @param array $post The POST data
     * @param array $session The session data
     */
    public function __construct(Site $site, array $post, array &$session) {

        $root = $site->getRoot();

        // Make sure all fields are set
        if ($post["email"] === "") {
            $session[User::NEW_USER_ERROR_MESSAGE] = "Please enter an email.";

            $this->redirect = "$root/new-user.php?e";
            return;
        }
        if ($post["username"] === "") {
            $session[User::NEW_USER_ERROR_MESSAGE] = "Please enter a username.";

            $this->redirect = "$root/new-user.php?e";
            return;
        }

        // Get input
        $username = strip_tags($post["username"]);
        $email = strip_tags($post["email"]);

        // Create a user
        $newUser = new User([
                                "email"    => $email,
                                "username" => $username,
                            ]);

        $users = new Users($site);

        // Add the user and get the new user's id
        $mailer = new Email();
        $id = $users->add($newUser,$mailer);

        // If the returned id is -1, there was a failure adding the user
        if ($id === -1) {
            $session[User::NEW_USER_ERROR_MESSAGE] = "Failed to sign up.";

            $this->redirect = "$root/new-user.php?e";
            return;
        }

        $session[User::NEW_USER_ERROR_MESSAGE] = "Check your email to continue.";
        $this->redirect = "$root/new-user.php?e";
    }
}
