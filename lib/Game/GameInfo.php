<?php


namespace Game;


class GameInfo {
    public function __construct($id, $player0, $player1, $player2, $player3, $gameState, $turn, $status){
        $this->id = $id;
        $this->turn = $turn;
        $this->status = $status;
        $this->game_state = $gameState;
        $this->players[] = $player0;
        if($player1 !== null) {
            $this->players[] = $player1;
        }
        if($player2 !== null) {
            $this->players[] = $player2;
        }
        if($player3 !== null) {
            $this->players[] = $player3;
        }
    }

    public function playerCount(){
        return count($this->players);
    }
    private $id;
    private $players;
    private $game_state;
    private $turn;
    private $status;

    public function getId()
    {
        return $this->id;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getGameState()
    {
        return $this->game_state;
    }

    public function getTurn()
    {
        return $this->turn;
    }

    public function getStatus()
    {
        return $this->status;
    }


}