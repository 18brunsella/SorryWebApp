<?php


class GamesTest extends \PHPUnit\Framework\TestCase {
    private static $site;

    public static function setUpBeforeClass() {
        self::$site = new Game\Site();
        $localize  = require 'localize.inc.php';
        if(is_callable($localize)) {
            $localize(self::$site);
        }
    }

    protected function setUp() {
        $games = new Game\Games(self::$site);
        $tableName = $games->getTableName();

        $sql = <<<SQL
delete from $tableName;
insert into $tableName(id,player0, player1, player2, gameState,turn,status)
values (1,46,null,49,"",0,-1),
       (2,48,43,49,"",0,-1)
SQL;

        self::$site->pdo()->query($sql);
    }



    public function test_pdo() {
        $games = new Game\Games(self::$site);
        $this->assertInstanceOf('\PDO', $games->pdo());
    }

    public function test_removeUser() {
        $games = new Game\Games(self::$site);
        $user = new \Game\User(array("id"=>43, "email" => "hey@gmail.com", "username" => "TestUser43"));
        $games->newGame($user);
        $user->setGameKey(2);
        $results = $games->removeUser($user);
        $this->assertEquals(false, $results);
    }

    public function test_shiftUser() {
        $games = new Game\Games(self::$site);
        $user = new \Game\User(array("id"=>46, "email" => "hey@gmail.com", "username" => "TestUser46"));
        $games->newGame($user);
        $user->setGameKey(1);
        $results = $games->shiftUsers($user);
        $this->assertEquals(false, $results);
    }
}