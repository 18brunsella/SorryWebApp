<?php


use Game\Game as Game;
use Game\Pawn as Pawn;
use Game\Tile as Tile;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase {
    public function testConstruct() {
        $g = new Game();

        $this->assertInstanceOf('Game\Game', $g);
    }

    public function testConstructWithSeed() {
        $g = new Game(1234);

        $this->assertInstanceOf('Game\Game', $g);
    }

    public function testAddPlayer() {
        $game = new Game();
        $this->assertCount(0, $game->getPlayers());
        $game->addPlayer(0);
        $this->assertCount(1, $game->getPlayers());
    }

    public function testCreateBoard() {
        $game = new Game();
        $game->createBoard();
        $this->assertNotEquals(null, $game->getBoard());
    }

    public function testCreateDeck() {
        $game = new Game();
        $game->createDeck();
        $this->assertNotEquals(null, $game->getCards());
    }

    public function testInitializePlayerPawns() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();

        $this->assertTrue($game->isGameReady());
        for ($i = 0; $i < 4; $i++) {
            $row = $game->getBoard()->getStartSpaces(0, $i)->getRow();
            $col = $game->getBoard()->getStartSpaces(0, $i)->getCol();
            $this->assertNotNull($game->getBoard()->getTile($row, $col)->getContains());
        }
    }

    public function testDrawCard() {
        $game = new Game();
        $game->addPlayer(0);
        $game->drawCard();
        $this->assertNotEquals(null, $game->getDiscard());
        $this->assertEquals(true, $game->getCardDrawn());
    }

    public function testCheckHasMoves() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();


        //check init with invalid card
        $game->setDiscard(4);
        $this->assertFalse($game->checkHasMoves());

        //check with valid card
        $game->setDiscard(2);
        $this->assertTrue($game->checkHasMoves());
    }

    public function testSorryAvail() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();

        $game->setDiscard(0);
        $this->assertFalse($game->checkHasMoves());
    }

    public function testIsGameOver() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();
        $tile = new Tile(0, 0);

        //test empty
        $this->assertFalse($game->isGameOver());

        //test almost full
        for ($i = 0; $i < 3; $i++) {
            $game->getBoard()->getHomeSpaces(0, $i)->setContains($tile);
        }
        $this->assertFalse($game->isGameOver());

        //test full
        for ($i = 0; $i < 4; $i++) {
            $game->getBoard()->getHomeSpaces(0, $i)->setContains($tile);
        }
        //TEMP
        $this->assertFalse($game->isGameOver());
    }

    public function testIncrementPlayerTurn() {
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();

        $this->assertEquals(0, $game->getPlayerTurn());
        $game->incrementPlayerTurn();
        $this->assertEquals(1, $game->getPlayerTurn());
        $game->incrementPlayerTurn();
        $this->assertEquals(0, $game->getPlayerTurn());

    }

    public function testNewGame() {
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();
        $game->drawCard();

        $game->newGame();
        $this->assertNull($game->getDiscard());
        $this->assertEquals(0, $game->getPlayerTurn());
        $this->assertFalse($game->getCardDrawn());
        $this->assertFalse($game->getPawnMoved());
    }

    public function testProcessCard() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();

        $this->assertEquals(0, $game->processCard());
        $game->setDiscard(3);
        $this->assertEquals(3, $game->processCard());
    }

    public function testProcessMove() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();

        //test basic case
        $game->setDiscard(1);
        $game->processMove(1, 4);
        $this->assertTrue($game->isPawnSelected());
        $this->assertEquals($game->getBoard()->getTile(1, 4), $game->getTileFrom());

        $game->processMove(0, 4);
        $this->assertNotNull($game->getBoard()->getTile(0, 4)->getContains());
        $this->assertNull($game->getBoard()->getTile(1, 4)->getContains());
        $this->assertNull($game->getTileFrom());
        $this->assertFalse($game->isPawnSelected());

        //test 2 card
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();

        $game->setDiscard(2);
        $game->processMove(1, 4);
        $game->processMove(0, 4);
        $this->assertFalse($game->getCardDrawn());
        $this->assertFalse($game->getPawnMoved());

        //test landing on another pawn
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();

        $pawn = new Pawn(1, 14, 4);
        $game->getBoard()->getTile(4, 14)->setContains(null);
        $game->getBoard()->getTile(0, 4)->setContains($pawn);


        $game->setDiscard(1);
        $game->processMove(1, 4);
        $this->assertTrue($game->isPawnSelected());
        $this->assertEquals($game->getBoard()->getTile(1, 4), $game->getTileFrom());

        $game->processMove(0, 4);
        $this->assertNotNull($game->getBoard()->getTile(0, 4)->getContains());
        $this->assertNull($game->getBoard()->getTile(1, 4)->getContains());
        $this->assertNull($game->getTileFrom());
        $this->assertFalse($game->isPawnSelected());
    }

    public function testProcessCardRule() {
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();
        $card_num = 3;
        $tile_clicked = $game->getBoard()->getTile(1, 4);

        //dont do anything if moved
        $game->setPawnMoved(true);
        $this->assertFalse($game->ProcessCardRule($tile_clicked, $card_num));

        //dont do anything if not 1 or 2 on start
        $game->setPawnMoved(false);
        $this->assertFalse($game->ProcessCardRule($tile_clicked, $card_num));

        $card_num = 2;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
        $card_num = 1;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));


        //test rest of cards cases
        $pawn = new Pawn(0, 0, 0);
        $tile_clicked = $game->getBoard()->GetTile(0, 4);
        $tile_clicked->setContains($pawn);
        $card_num = 3;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
        $card_num = 4;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
        $card_num = 7;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
        $card_num = 10;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
        $card_num = 5;
        $this->assertTrue($game->ProcessCardRule($tile_clicked, $card_num));
    }

    public function testSwap() {
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();

        $pawn = new Pawn(0, 0, 0);
        $game->getBoard()->getTile(0, 0)->setContains($pawn);
        $pawn = new Pawn(1, 1, 0);
        $game->getBoard()->getTile(1, 0)->setContains($pawn);

        $tile_from = $game->getBoard()->getTile(0, 0);
        $game->setTileFrom($tile_from);

        $tile_clicked = $game->getBoard()->getTile(1, 0);

        $game->swapPawns($tile_clicked);

        $this->assertEquals(0, $game->getBoard()->getTile(1, 0)->getContains()->getColor());
        ////////////////////////////////////////////////
        $pawn = new Pawn(0, 0, 0);
        $game->getBoard()->getTile(0, 0)->setContains($pawn);
        $pawn = new Pawn(0, 1, 0);
        $game->getBoard()->getTile(1, 0)->setContains($pawn);

        $tile_from = $game->getBoard()->getTile(0, 0);
        $game->setTileFrom($tile_from);

        $tile_clicked = $game->getBoard()->getTile(1, 0);

        $game->swapPawns($tile_clicked);

        $this->assertEquals(0, $game->getBoard()->getTile(1, 0)->getContains()->getColor());
    }

    public function testSorry() {
        $game = new Game();
        $game->addPlayer(0);
        $game->addPlayer(1);
        $game->initializePlayerPawns();

        $pawn = new Pawn(0, 0, 0);
        $game->getBoard()->getTile(0, 0)->setContains($pawn);
        $pawn = new Pawn(1, 1, 0);
        $game->getBoard()->getTile(1, 0)->setContains($pawn);

        $tile_from = $game->getBoard()->getTile(0, 0);
        $game->setTileFrom($tile_from);

        $tile_clicked = $game->getBoard()->getTile(1, 0);

        $game->sorry($tile_clicked);

        $this->assertEquals(0, $game->getBoard()->getTile(1, 0)->getContains()->getColor());
    }

    public function testSlide() {
        $game = new Game();
        $game->addPlayer(1);
        $game->initializePlayerPawns();

        // Pawn is a different color from slide
        $pawn = new Pawn(1, 0, 0);
        $tile_clicked = $game->getBoard()->getTile(0, 1);
        $game->getBoard()->getTile(0, 0)->setContains($pawn);
        $game->setTileFrom($game->getBoard()->getTile(0, 0));
        $this->assertTrue($game->checkSlide($tile_clicked));

        // Pawn is the same color as slide so no slide
        $pawn = new Pawn(0, 0, 0);
        $game->getBoard()->getTile(0, 0)->setContains($pawn);
        $this->assertFalse($game->checkSlide($tile_clicked));

    }

    public function testResetFlags() {
        $game = new Game();
        $tile = $game->getBoard()->getTile(0, 1);
        $tile->setReachable(true);
        $tile->setOnPath(true);
        $tile->setBlocked(true);
        $game->resetTileFlags();
        $tile = $game->getBoard()->getTile(0, 1);
        $this->assertEquals(false, $tile->getOnPath());
        $this->assertEquals(false, $tile->getBlocked());
        $this->assertEquals(false, $tile->getReachable());

    }

    public function testIsValidSwap() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();
        $game->setDiscard(11);


        $tile_clicked_norm = $game->getBoard()->getTile(0, 0);
        $tile_clicked_home = $game->getBoard()->getTile(6, 1);
        $tile_clicked_safe = $game->getBoard()->getTile(1, 2);
        $tile_clicked_start = $game->getBoard()->getTile(1, 4);

        $this->assertFalse($game->isValidSwap($tile_clicked_home));
        $this->assertFalse($game->isValidSwap($tile_clicked_start));
        $this->assertFalse($game->isValidSwap($tile_clicked_safe));
        $this->assertFalse($game->isValidSwap($tile_clicked_norm));

        $tile_clicked_norm->setContains(new Pawn(0, 0, 0));
        $this->assertFalse($game->isValidSwap($tile_clicked_norm));
    }

    public function testGetterSetters() {
        $game = new Game();
        $game->addPlayer(0);
        $game->initializePlayerPawns();
        $game->drawCard();

        $this->assertEquals(0, $game->getPlayerTurn());
        $this->assertInstanceOf("Game\Card", $game->getDiscard());
        $this->assertInstanceOf("Game\Cards", $game->getCards());

        $this->assertFalse($game->getPawnMoved());
        $game->setPawnMoved(true);
        $this->assertTrue($game->getPawnMoved());

        $game->setTurnOver(false);
        $this->assertFalse($game->isTurnOver());
        $game->setTurnOver(true);
        $this->assertTrue($game->isTurnOver());

        $game->setCardDrawn(3);
        $this->assertEquals(3, $game->getCardDrawn());

        $game->setPawnMoved(true);
        $this->assertTrue($game->getPawnMoved());

        $this->assertCount(1, $game->getPlayers());


        $game->setDiscard(3);
        $this->assertEquals(3, $game->getDiscard()->getDescription());

        $tile_from = $game->getBoard()->GetTile(0, 0);
        $this->assertEquals($game->getBoard()->getTile(0, 0), $tile_from);
    }

    public function testSetReachableCount() {
        $game = new Game();

        $game->setReachableCount(10);

        $this->assertInstanceOf('Game\Game', $game);
    }

}
