<?php
require_once("player.php");
session_start();
$player = unserialize($_SESSION['player']);
?>

<html>

<body>
<?php
/**
 * Parse and handel the command receive from player
 */
$command = $_POST['command'];
$m = explode(" ", $command);
$m1 = strtolower($m[0]);
$message = substr(strstr($command, " "),1);
$outPutmessage ="";
switch ($m1){
    case "say":
        say($player->getname(), $message, $player->getRoom());
        break;
    case "tell":
        $message = substr(strstr($message, " "),1);
        if($m[1] && $message){
            tell($player->getname(), $m[1], $message);
        }else{
            $outPutmessage = "invalid command, need to be tell (person) (dialog)";
        }
        break;
    case "yell":
        if($message){
            yell($player->getname(), $message);
        }else{
            $outPutmessage = "invalid command, need to be yell (dialog)";
        }
        break;
    case "north":
        $result = move($player->getname(),$player->getRoom(),$player->x,$player->y + 1,$player->z);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x,$player->y + 1,$player->z);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    case "south":
        $result = move($player->getname(),$player->getRoom(),$player->x,$player->y - 1,$player->z);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x,$player->y - 1,$player->z);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    case "east":
        $result = move($player->getname(),$player->getRoom(),$player->x + 1,$player->y,$player->z);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x + 1,$player->y,$player->z);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    case "west":
        $result = move($player->getname(),$player->getRoom(),$player->x - 1,$player->y,$player->z);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x - 1,$player->y,$player->z);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    case "up":
        $result = move($player->getname(),$player->getRoom(),$player->x,$player->y,$player->z + 1);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x,$player->y,$player->z + 1);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    case "down":
        $result = move($player->getname(),$player->getRoom(),$player->x,$player->y,$player->z - 1);
        if($result) {
            $outPutmessage = $result;
        } else{
            $player->setRoom($player->x,$player->y,$player->z - 1);
            $outPutmessage = "You Enter Room".$player->getRoom();
        }
        break;
    default:
        if($m1){
            $outPutmessage = "invalid command<Br>command list:<br> say (dialog): to say something in current room<br> yell (dialog): to say something in global <br> tell (user_name) (dialog): to whisper someone <br> move by north, south, east, west, up, down";

        }
}
    $_SESSION['player'] = serialize($player);
?>
<div><h2><?php echo "ROOM".$player->getRoom(); ?></h2></div>
<div><h6><?php echo "Room Description: You are in Room".$player->getRoom();?></h6></div>
<?php
    echo $outPutmessage;
?>
<form action="command.php" method="post">
    command: <input type="text" name="command">
    <input type="submit"> </form>
</body>


</html>
