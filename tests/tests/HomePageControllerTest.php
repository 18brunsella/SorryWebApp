<?php


use Game\Game;
use Game\HomePageController;
use PHPUnit\Framework\TestCase;

class HomePageControllerTest extends TestCase {
    public function test_construct_nothing() {
        $game = new Game();
        $post = [];

        $con = new HomePageController($game, $post);

        $this->assertFalse($con->isValid(), 'Game is not valid');
        $this->assertEmpty($game->getPlayers(), 'No players added');
    }

    public function test_construct_one_player() {
        $game = new Game();
        $post = ['playerOne' => 'test player #1'];

        $con = new HomePageController($game, $post);

        $this->assertTrue($con->isValid(), 'Game is valid');
        $this->assertCount(1, $game->getPlayers(), 'One player added');
    }

    public function test_construct_two_players() {
        $game = new Game();
        $post = ['playerOne' => 'test player #1', 'playerTwo' => 'test player #2'];

        $con = new HomePageController($game, $post);

        $this->assertTrue($con->isValid(), 'Game is valid');
        $this->assertCount(2, $game->getPlayers(), 'Two players added');
    }

    public function test_construct_three_players() {
        $game = new Game();
        $post = ['playerOne' => 'test player #1', 'playerTwo' => 'test player #2', 'playerThree' => 'test player #3'];

        $con = new HomePageController($game, $post);

        $this->assertTrue($con->isValid(), 'Game is valid');
        $this->assertCount(3, $game->getPlayers(), 'Three players added');
    }

    public function test_construct_all_players() {
        $game = new Game();
        $post = ['playerOne' => 'test player #1', 'playerTwo' => 'test player #2', 'playerThree' => 'test player #3', 'playerFour' => 'test player #4'];

        $con = new HomePageController($game, $post);

        $this->assertTrue($con->isValid(), 'Game is valid');
        $this->assertCount(4, $game->getPlayers(), 'All four players added');
    }
}
