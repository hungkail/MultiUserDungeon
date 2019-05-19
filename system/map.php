<?php
require_once("config.php");
/** map.php
 *  Here is most of the backend function declared
 */

/**
 * @param string $tableName
 *
 * taking a name and initialize the empty dungeon
 */
function initializeMap(string $tableName) {
    pg_query("CREATE TABLE ".$tableName."(roomID char(8), players text, solid boolean, PRIMARY KEY(roomID));");
}

/**
 * @param string $tableName
 * @param int $length
 * @param int $width
 * @param int $height
 *
 * Taking config params and resize the dungeon
 */
function resizeMap(string $tableName, int $length, int $width, int $height) {
    pg_query("DELETE FROM ".$tableName);

    for($i = 0; $i < $length; $i++) {
        for($j = 0; $j < $width; $j++){
            for($k = 0; $k < $height; $k++) {
                $roomID = "(".$i.",".$j.",".$k.")";
                if($roomID != "(1,1,1)"){
                    $bool = mt_rand(1,10) > 8 ? 'True': 'False';
                    pg_query("INSERT INTO ".$tableName." VALUES (".$roomID.", '', ".$bool.");" );
                }else{
                    pg_query("INSERT INTO ".$tableName." VALUES (".$roomID.", '', False);" );
                }

            }
        }
    }
}

/**
 * @param string $name          Player name
 * @param string $newRoom       The room player enter
 *
 * Doing Database query for changing where Player located
 * Mostly need to use leaveRoom function with it
 */
function enterRoom(string $name, string $newRoom) {
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT players FROM dungeons WHERE roomID = '".$newRoom."';");
    $players = pg_fetch_result($result, 0, 0);
    if($players == ''){
        $players = "(".$name.")";
    } else if(!preg_match("/(".$name.")/", $players)){
        $players .= " (".$name.")";
    }

    pg_query("UPDATE dungeons SET players = '".$players."' WHERE roomID = '".$newRoom."';");
    pg_close($dbconn);

}

/**
 * @param string $name               Player name
 * @param string $currentRoom        The room player current in
 *
 * Doing Database query for changing where Player located
 * Mostly need to use enterRoom with it
 */
function leaveRoom(string $name, string $currentRoom){
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT players FROM dungeons WHERE roomID = '".$currentRoom."';");
    $players = pg_fetch_result($result, 0, 0);
    if (preg_match("/(".$name.")/", $players)){
        $players = str_replace("(".$name.")", "", $players);
    }
    pg_query("UPDATE dungeons SET players = '".$players."' WHERE roomID = '".$currentRoom."';");
    pg_close($dbconn);
}

/**
 * @param string $name      Player name
 * @return string           Display top 10 message Player received
 *
 * This function is use for chat box
 */
function displayMessage(string $name) {
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT * FROM (SELECT * FROM ".$name." ORDER BY id DESC LIMIT 10) a ORDER BY a.id ASC;");
    $message = "";
    if($result){
        while ($m = pg_fetch_assoc($result)){
            $message .= '<div class="message">'.$m['username'].": ".$m['message'].'</div>';
        }
    } else{
        $message = "ERROR!!! HAS PROBLEM CONNECTING DATABASE";
    }
    pg_close($dbconn);

    return $message;
}

/**
 * @param string $room      Where Player located
 * @return string           All the Players in same room
 *
 * This function is use to show all player in same room
 */
function displayPlayers(string $room) {
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT players FROM dungeons WHERE roomid = '".$room."';");
    $message = "";
    if($result){
        $m = pg_fetch_assoc($result);
        $m = $m['players'];
        preg_match_all("(\([\w\s]+\))", $m, $re);
        foreach($re[0] as $e) {
            $message.= $e."<BR>";
        }



    } else{
        $message = "ERROR!!! HAS PROBLEM CONNECTING DATABASE";
    }
    pg_close($dbconn);
    return $message;
}

/**
 * @param string $user          Player's name
 * @param string $message       What player want to say
 * @param string $room          Where all players inside this room should receive message
 *
 * To have all players inside same room to receive message
 */
function say(string $user, string $message, string $room){
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT players FROM dungeons WHERE roomid = '".$room."';");

    if($result){
        $m = pg_fetch_assoc($result);
        $m = $m['players'];

        preg_match_all("(\([\w\s]+\))", $m, $re);
        foreach($re[0] as $e) {
            $e = substr($e,1,strlen($e) - 2);
            pg_query("INSERT INTO ".$e." VALUES (Default, '".$user."','".$message."' );");
        }



    } else{
        echo "ERROR!!! HAS PROBLEM CONNECTING DATABASE";
    }
    pg_close($dbconn);

}

/**
 * @param string $from      This player's name
 * @param string $to        Other player's name
 * @param $message          The message this player want to send to other player
 *
 * Send a message to Other player if he/she exists
 */
function tell(string $from, string $to, $message){
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = @pg_query("INSERT INTO ".$to." VALUES (Default, 'whisper from ".$from."','".$message."' );");
    if($result){
        pg_query("INSERT INTO ".$from." VALUES (Default, 'whisper to ".$to."','".$message."' );");
    }else{
        @pg_query("INSERT INTO ".$from." VALUES (Default, 'whisper to ".$to."','Error: There is no player name ".$to."' );");
    }
    pg_close($dbconn);
}

/**
 * @param string $user          Player's name
 * @param string $message       What player want to say globally
 *
 * Send message to all players inside dungeon
 */
function yell(string $user, string $message) {
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT roomID FROM dungeons;");
    pg_close($dbconn);

    if($result){
        while ($m = pg_fetch_assoc($result)){
           say($user, $message, $m['roomid']);
        }
    } else{
        echo "ERROR!!! HAS PROBLEM CONNECTING DATABASE";
    }

}

/**
 * @param string $room      room name
 * @return bool             is solid or not
 *
 * Check if certain room is solid, so can not be enter
 */
function isSolid(string $room){
    global $conn_string;
    $dbconn = pg_connect($conn_string);
    $result = pg_query("SELECT solid FROM dungeons WHERE roomid = '".$room."';");
    if($result){
       $m = pg_fetch_assoc($result);
       $result = $m['solid'];

    } else{
        $result =  "ERROR!!! HAS PROBLEM CONNECTING DATABASE";
    }
    pg_close($dbconn);
    return $result == 't';
}

/**
 * @param string $user              Player's name
 * @param string $currentRoom       Player's current room
 * @param int $x                    new x-coordinate for where player gonna move to
 * @param int $y                    new y-coordinate for where player gonna move to
 * @param int $z                    new z-coordinate for where player gonna move to
 * @return string|null              if return null, everything is good, else return error message
 *
 * a helper function for move command, it would also check if move action is valid
 */
function move(string $user, string $currentRoom, int $x, int $y, int $z){
    global $dungeonLength, $dungeonWidth, $dungeonHeight;
    if($x >= $dungeonLength || $x < 0 || $y >= $dungeonWidth || $y < 0 || $z >= $dungeonHeight || $z < 0) {
        return "can not move outside of the dungeon!!";
    }
    $newRoom = '('.$x.','.$y.','.$z.')';
    if(isSolid($newRoom)){
        return "invalid move, the room is solid!!";
    }
    leaveRoom($user, $currentRoom);
    enterRoom($user, $newRoom);
    return null;
}

/**
 * Logout function
 * It will clear Player from room and logout
 * But the data for player will still be preserved
 * Need this function because it is hard to tell if player close the browser or not
 */
function logout(){
    session_start();
    $player = unserialize($_SESSION['player']);
    leaveRoom($player->getname(), $player->getRoom());
}
?>
