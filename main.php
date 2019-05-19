<?php
include_once("./system/map.php");
include_once("./system/player.php");
session_start();

//put player object in SEESION
$player = unserialize($_SESSION['player']);

?>

<html>
<head>
    <title>MultiUser Dungeon</title>

</head>
<body>

<iframe src="chatScreen.php" align="left" height="600px"></iframe>
<iframe src="playerScreen.php" align="right" height="600px"></iframe>
<iframe src="./system/command.php" align="middle" height="600px" "></iframe>
</body>
<form action="logout.php">
    <input type="submit" value="Click Me To Log Out!!">
</form>
</html>
