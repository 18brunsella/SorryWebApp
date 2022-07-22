<?php


namespace Game;


class Games extends Table {
    const IN_LOBBY = -1;
    const GAME_STARTED = 0;
    const GAME_COMPLETE = 1;

    public function __construct(Site $site) {
        parent::__construct($site, "game");
    }

    public function newGame(User $user) {
        $sql = <<<SQL
INSERT INTO $this->tableName(id,player0, status)
     VALUES (?, ?, ?);
SQL;

        $gameKey = self::randomSalt();

        $user->setGameKey($gameKey);

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([$gameKey, $user->getId(), -1]);
    }

    public function addUserToGame(User $user,$game_id){
        $avail_spot = $this->getAvailSpot($game_id);
        if($avail_spot == -1 || $this->getStatus($game_id) != $this::IN_LOBBY){
            return false;
        }
        $spot = "player".$avail_spot;
        try{
            $sql = <<<SQL
UPDATE $this->tableName
set $spot=?
where id=?
SQL;
            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute([$user->getId(),$game_id]);

            $user->setGameKey($game_id);
        }
        catch (\PDOException $e){
            return false;
        }
        return true;
    }

    public function getAvailSpot($game_id) {
        $sql = <<<SQL
SELECT player0,player1,player2,player3
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$game_id]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if($row['player1'] === null){
            return 1;
        }
        elseif($row['player2'] === null){
            return 2;
        }
        elseif($row['player3'] === null){
            return 3;
        }
        else{
            return -1;
        }
    }

    public function get($game_id){
        $players = [];
        $sql = <<<SQL
SELECT player0, player1, player2, player3
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$game_id]);
        if($statement->rowCount() === 0){
            return null;
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getStatus($game_id){
        $sql = <<<SQL
SELECT status
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$game_id]);
        return $statement->fetch(\PDO::FETCH_ASSOC)['status'];
    }
    public function updateStatus($status,$game_id){
        try {
            $sql = <<<SQL
UPDATE $this->tableName
set status=?
where id=?
SQL;

            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute([$status, $game_id]);
        }
        catch (\PDOException $e){
            return false;
        }
        return true;
    }

    public function updateGameState($game_state, $turn, $status, $game_id){
        try {
            $sql = <<<SQL
UPDATE $this->tableName
set gameState=?, turn=?,status=?
where id=?
SQL;

            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute([$game_state,$turn,$status, $game_id]);
        }
        catch (\PDOException $e){
            return false;
        }
        return true;
    }

    public function getGameState($game_id){
        $sql = <<<SQL
SELECT gameState
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$game_id]);
        return $statement->fetch(\PDO::FETCH_ASSOC)['gameState'];
    }

    public function getGames(){
        $ret_arr = [];
        $sql = <<<SQL
SELECT *
from $this->tableName
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([]);

        foreach($statement as $row){
            $game_info = new GameInfo($row['id'],$row['player0'],$row['player1'],$row['player2'],
                                        $row['player3'],$row['gameState'],$row['turn'],$row['status']);
            $ret_arr[] = $game_info;
        }

        return $ret_arr;
    }

    public function isUserInStatus($user,$status){
        $sql = <<<SQL
SELECT id
from $this->tableName
where (player0 = ? or player1 = ? or player2 = ? or player3 = ?) and status = ?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$user->getId(),$user->getId(),$user->getId(),$user->getId(),$status]);
        if($statement->rowCount() === 0){
            return null;
        }
        return $statement->fetch(\PDO::FETCH_ASSOC)['id'];
    }

    public function isPlayerTurn($user){
        $sql = <<<SQL
SELECT turn
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$user->getGameKey()]);
        //if($statement->rowCount() === 0){
        //    return null;
        //}
        $turn = 'player'.$statement->fetch(\PDO::FETCH_ASSOC)['turn'];
        $sql = <<<SQL
SELECT $turn
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$user->getGameKey()]);

        if($statement->fetch(\PDO::FETCH_ASSOC)[$turn] == $user->getId()){
            return true;
        }
        return false;

    }

    public function getTurn($user) {
        $sql = <<<SQL
SELECT turn
from $this->tableName
where id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$user->getGameKey()]);
        return $statement->fetch(\PDO::FETCH_ASSOC)['turn'];
    }
    public function isPlayerHost($user) {
        $sql = <<<SQL
SELECT player0
from $this->tableName
where id = ?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute([$user->getGameKey()]);
        $host_id = $statement->fetch(\PDO::FETCH_ASSOC)['player0'];
        if($host_id == $user->getId()) {
            return true;
        }
        return false;
    }
    public function removeGame($user) {
        $sql = <<<SQL
DELETE FROM $this->tableName
where id = ?
SQL;
        if($this->isPlayerHost($user)){
            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute([$user->getGameKey()]);
            return true;
        }
        return false;
    }

    /**
     * Simple helper to debug to the console
     *
     * @param $data object, array, string $data
     * @param $context string  Optional a description.
     *
     * @return string
     */
    public function debug_to_console($data, $context = 'Debug in Console') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . json_encode($data) . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }

    public function shiftUsers($user) {
        $game_id = $user->getGameKey();
        $row = $this->get($game_id);
        $row = $row[0];
        for ($n = 0; $n < 2; $n++) {
            for ($i = 0; $i < 3; $i++){
                if ($row['player' . $i] === null){
                    if ($row['player' . ($i+1)] !== null){
                        $col_id = 'player' . $i;
                        $shift_id = $row['player' . ($i+1)];
                        try {
                            $sql = "";
                            // not sure why i have to break these up like this, but PDO didnt like ? = null then taking a var

                            if ($col_id == 'player1')
                            {
                                $sql = <<<SQL
UPDATE $this->tableName
set player1 = ?, player2 = null
SQL;
                            }
                            elseif ($col_id == 'player2')
                            {
                                $sql = <<<SQL
UPDATE $this->tableName
set player2 = ?, player3 = null
SQL;
                            }


                            $pdo = $this->pdo();
                            $statement = $pdo->prepare($sql);
                            $statement->execute([$shift_id]);
                            if($statement->rowCount() === 0){
                                return "sql error";
                            }
                        }
                        catch (\PDOException $e){
                            return "pdo exception";
                        }
                    }
                }
            }
        }

    }

    public function removeUser($user) {
        if ($this->removeGame($user)){
            return false;
        }
        $game_id = $user->getGameKey();
        $row = $this->get($game_id);
        $row = $row[0];
        if($row['player0'] == $user->getId()){
            $col_id = "player0";
        }
        elseif($row['player1'] == $user->getId()){
            $col_id = "player1";
        }
        elseif($row['player2'] == $user->getId()){
            $col_id = "player2";
        }
        elseif($row['player3'] == $user->getId()){
            $col_id = "player3";
        }
        else{
            return false;
        }

        try {
            $sql = "";
            // not sure why i have to break these up like this, but PDO didnt like ? = null then taking a var

            if ($col_id == 'player1')
            {
                $sql = <<<SQL
UPDATE $this->tableName
set player1 = null
SQL;
            }
            elseif ($col_id == 'player2')
            {
                $sql = <<<SQL
UPDATE $this->tableName
set player2 = null
SQL;
            }
            elseif ($col_id == 'player3')
            {
                $sql = <<<SQL
UPDATE $this->tableName
set player3 = null
SQL;
            }

            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute();
        }
        catch (\PDOException $e){
            return false;
        }

        $this->shiftUsers($user);

        return true;

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
}
