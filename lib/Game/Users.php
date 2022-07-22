<?php


namespace Game;


use Game\User;

/**
 * Class representing the User table
 * @package Game
 */
class Users extends Table {
    /**
     * Users constructor.
     *
     * @param Site $site
     */
    public function __construct(Site $site) {
        parent::__construct($site, "user");
    }

    /**
     * Add a new user
     *
     * @param User $user The user object to add
     *
     * @return int The new user's id, or -1 on error
     */
    public function add(User $user, Email $mailer): int {
        if ($this->exists($user->getEmail())) {
            return -1;
        }

        $sql = <<<SQL
INSERT INTO $this->tableName(email, username)
     VALUES (?, ?);
SQL;

        $statement = $this->pdo()->prepare($sql);

        $statement->execute([
                                $user->getEmail(),
                                $user->getUsername(),
                            ]);

        $id = $this->pdo()->lastInsertId();

        $validators = new Validators($this->site);
        $validator = $validators->newValidator($id);
        // Send email with the validator in it
        $link = "http://webdev.cse.msu.edu"  . $this->site->getRoot() .
            '/password-validate.php?v=' . $validator;

        $from = $this->site->getEmail();
        $name = $user->getUsername();

        $subject = "Confirm your email";
        $message = <<<MSG
<html>
<p>Greetings, $name,</p>

<p>Welcome to Sorry. In order to complete your registration,
please verify your email address by visiting the following link:</p>

<p><a href="$link">$link</a></p>
</html>
MSG;
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso=8859-1\r\nFrom: $from\r\n";
        $mailer->mail($user->getEmail(), $subject, $message, $headers);

        return $id;
    }

    /**
     * Check if a user exists
     *
     * @param string $email The email to check
     *
     * @return bool
     */
    public function exists(string $email): bool {
        $sql = <<<SQL
SELECT id
  FROM $this->tableName
 WHERE email = ?;
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute([$email]);

        return $statement->rowCount() !== 0;
    }

    /**
     * Set a user's password
     *
     * @param int $id A user's id
     * @param string $password The new password
     *
     * @return bool If the update was successful
     */
    public function setPassword(int $id, string $password): bool {
        $sql = <<<SQL
UPDATE $this->tableName
   SET password = ?,
       salt = ?
 WHERE id = ?;
SQL;

        $statement = $this->pdo()->prepare($sql);

        $salt = $this->randomSalt();
        $hash = hash("sha256", $password . $salt);

        $statement->execute([$hash, $salt, $id]);

        return $statement->rowCount() !== 0;
    }
    /**
     * Get a user based on the id
     * @param $id ID of the user
     * @return User object if successful, null otherwise.
     */
    public function get($id) {
        $sql =<<<SQL
SELECT * from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));
        if($statement->rowCount() === 0) {
            return null;
        }
        return new User($statement->fetch(\PDO::FETCH_ASSOC));
    }
    /**
     * Generate a salt
     *
     * @param int $len The length of the salt, default 16
     *
     * @return string The salt
     */
    public static function randomSalt($len = 16): string {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    /**
     * Test for a valid login.
     * @param $email User email
     * @param $password Password credential
     * @return User object if successful, null otherwise.
     */
    public function login($email, $password) {
        $sql =<<<SQL
SELECT * from $this->tableName
where email=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute([$email]);
        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        // Get the encrypted password and salt from the record
        $hash = $row['password'];
        $salt = $row['salt'];

        // Ensure it is correct
        if($hash !== hash("sha256", $password . $salt)) {
            return null;
        }

        return new User($row);
    }
}