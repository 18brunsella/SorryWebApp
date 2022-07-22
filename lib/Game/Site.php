<?php


namespace Game;


use PDO;
use PDOException;

/**
 * Class Site
 * @package Game
 */
class Site {
    const LOBBY_KEY = "nC8mm^V5%QiU-e=6";

    /**
     * @var PDO Database connection
     */
    private static $pdo = null;
    /**
     * @var string Database login email
     */
    private $email = "";
    /**
     * @var string Database host
     */
    private $dbHost = null;
    /**
     * @var string Database login user
     */
    private $dbUser = null;
    /**
     * @var string Database login password
     */
    private $dbPass = null;
    /**
     * @var string Prefix for database table names
     */
    private $tablePrefix = "";
    /**
     * @var string Site URL root
     */
    private $root = "";

    /**
     * Get the prefix for database table names.
     *
     * @return string Table prefix
     */
    public function getTablePrefix(): string {
        return $this->tablePrefix;
    }

    /**
     * Get the database login email.
     *
     * @return string Database login email
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set the database login email.
     *
     * @param string $email Database login email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * Get the site URL root.
     *
     * @return string Site URL root
     */
    public function getRoot(): string {
        return $this->root;
    }

    /**
     * Set the site URL root.
     *
     * @param string $root Site URL root
     */
    public function setRoot(string $root) {
        $this->root = $root;
    }

    /**
     * Configure the database.
     *
     * @param string $host Database login host
     * @param string $user Database login user
     * @param string $pass Database login password
     * @param string $prefix Table prefix
     */
    public function dbConfigure(string $host, string $user, string $pass, string $prefix) {
        $this->dbHost = $host;
        $this->dbUser = $user;
        $this->dbPass = $pass;
        $this->tablePrefix = $prefix;
    }

    /**
     * Get or create the database PDO object.
     *
     * @return PDO Database PDO object
     */
    function pdo(): PDO {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            self::$pdo = new PDO($this->dbHost, $this->dbUser, $this->dbPass);
        } catch (PDOException $e) {
            die("Unable to connect to database:\n" . $e);
        }

        return self::$pdo;
    }
}
