<?php


namespace Game;


class Validators extends Table {
    /**
     * Constructor
     * @param $site The Site object
     */
    public function __construct(Site $site) {
        parent::__construct($site, "validator");
    }
    /**
     * Create a new validator and add it to the table.
     * @param $userid User this validator is for.
     * @return The new validator.
     */
    public function newValidator($userid) {
        $validator = $this->createValidator();

        // Write to the table
        $sql = <<<SQL
INSERT INTO $this->tableName
VALUES(?,?,?)
SQL;
        $statement = $this->pdo()->prepare($sql);
        $statement->execute([$validator, $userid, date("Y-m-d H:i:s")]);

        return $validator;
    }
    /**
     * Generate a random validator string of characters
     * @param $len Length to generate, default is 32
     * @return Validator string
     */
    public function createValidator($len = 32) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    /**
     * Determine if a validator is valid. If it is,
     * return the user ID for that validator.
     * @param $validator Validator to look up
     * @return User ID or null if not found.
     */
    public function get($validator) {
        $sql =<<<SQL
SELECT userid from $this->tableName
where validator=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($validator));
        if($statement->rowCount() === 0) {
            return null;
        }
        $id = $statement->fetch(\PDO::FETCH_ASSOC)['userid'];
        return $id;
    }

    /**
     * Remove any validators for this user ID.
     * @param $userid The USER ID we are clearing validators for.
     */
    public function remove($userid) {
        try{
            $sql = <<<SQL
DELETE FROM $this->tableName
WHERE userid=$userid
SQL;
            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute(array($userid));
        }
        catch(\PDOException $e){
            return false;
        }
        return true;
    }
}