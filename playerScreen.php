<?php
session_start();
?>
<meta http-equiv="refresh" content="5">
<html>
<h2>Players</h2>
</html>

<?php
require_once("./system/map.php");
require_once("./system/player.php");
$player = unserialize($_SESSION['player']);
echo displayPlayers($player->getroom());
?>
