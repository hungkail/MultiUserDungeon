<?php
session_start();
?>
/* refresh page every 2 second
   in order to show current data of players in the room */
<meta http-equiv="refresh" content="2">
<html>
<h2>Players</h2>
</html>

<?php
require_once("./system/map.php");
require_once("./system/player.php");
$player = unserialize($_SESSION['player']);
echo displayPlayers($player->getroom());
?>
