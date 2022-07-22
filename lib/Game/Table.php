<?php


namespace Game;


use PDO;

class Table {
    protected $site;      // The Site Object
    protected $tableName; // The table name to use

    /**
     * Table constructor.
     *
     * @param Site $site The site object
     * @param string $name The base table name
     */
    public function __construct(Site $site, string $name) {
        $this->site = $site;
        $this->tableName = $site->getTablePrefix() . $name;
    }

    /**
     * Get the database table name
     * @return string The table name
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * Database connection function
     * @return PDO object that connects to the database
     */
    public function pdo(): PDO {
        return $this->site->pdo();
    }
}
