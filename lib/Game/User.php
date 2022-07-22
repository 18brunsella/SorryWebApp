<?php


namespace Game;


/**
 * Class representing a User
 * @package Game
 */
class User {
    /**
     * The name used to identify the user in the session.
     */
    const SESSION_NAME = "user";
    /**
     * The name used to identify an error message in the session.
     * Used when there is an error adding a new user.
     */
    const NEW_USER_ERROR_MESSAGE = "new_user_error_message";

    /**
     * @var int The user's id
     */
    private $id;
    /**
     * @var string The user's email
     */
    private $email;
    /**
     * @var string The user's username
     */
    private $username;

    private $game_key;
    /**
     * User constructor.
     *
     * @param array $row The user data
     */
    public function __construct(array $row) {
        if (isset($row["id"])) {
            $this->id = $row['id'];
        }
        $this->email = $row['email'];
        $this->username = $row['username'];
    }

    /**
     * @return int The user's id
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string The user's email
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return string The user's username
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getGameKey()
    {
        return $this->game_key;
    }

    /**
     * @param mixed $game_key
     */
    public function setGameKey($game_key)
    {
        $this->game_key = $game_key;
    }

}
