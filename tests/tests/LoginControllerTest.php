<?php


class LoginControllerTest extends \PHPUnit\Framework\TestCase
{
    private static $site;

    public static function setUpBeforeClass() {
        self::$site = new Game\Site();
        $localize  = require 'localize.inc.php';
        if(is_callable($localize)) {
            $localize(self::$site);
        }
    }


    protected function setUp() {
        $users = new Game\Users(self::$site);
        $tableName = $users->getTableName();

        $sql = <<<SQL
TRUNCATE TABLE $tableName;
INSERT INTO $tableName(id, email, username, password, salt)
     VALUES (5, 'test@example.com', 'user', '8e4ad4596425017c8cd1accab5a028f1ac2177b09c5b222b7957a507fb99b8ae', '#+2(e(*j()gv1+nY');
SQL;

        self::$site->pdo()->query($sql);
    }

    public function test_construct() {
        $session = array();	// Fake session
        $root = self::$site->getRoot();
        $users = new Game\Users(self::$site);
        $tableName = $users->getTableName();

        // login
        $controller = new Game\LoginController(self::$site, $session,
            array("email" => "test@example.com", "password" => "test123"));

        $this->assertEquals("test_p2_user", $tableName);
        $this->assertEquals("user", $session[Game\User::SESSION_NAME]->getUsername());
        $this->assertEquals("$root/matchmaking.php", $controller->getRedirect());

        // Invalid login
        $controller = new Game\LoginController(self::$site, $session,
            array("email" => "bart@bartman.com", "password" => "wrongpassword"));

        $this->assertNull($session[Game\User::SESSION_NAME]);
        $this->assertEquals("$root/login.php?e", $controller->getRedirect());
    }

}