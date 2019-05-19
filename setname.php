<?php
ob_start();
require_once("./system/map.php");
require_once("./system/player.php");
require_once ("./system/config.php");
session_start();
$_SESSION['user_name'] = $_POST['playername'];

$player = new player($_SESSION['user_name'],1,1,1);
$_SESSION['player'] = serialize($player);
$conn_string = "host=localhost port=5434 dbname=sample user=postgres password= options='--client_encoding=UTF8'";

// Establish a connection with MySQL server
$dbconn = pg_connect($conn_string);

// Check connection status. Exit in case of errors
if(!$dbconn) {
    echo "Error: Unable to open database\n";
} else {
    echo "Opened database successfully\n";
}

$result = @pg_query("select count(*) from dungeons");
if(!$result) {
    initializeMap("dungeons");
    $result = pg_query("select count(*) from dungeons");
}
$totalRooms = $dungeonWidth*$dungeonLength*$dungeonHeight;
$currentRooms = pg_fetch_result($result,0,0);
if($currentRooms != $totalRooms) {
    resizeMap("dungeons", $dungeonLength, $dungeonWidth, $dungeonHeight);
}
@pg_query("CREATE TABLE ".$_SESSION['user_name']." (id serial, userName CHAR(255), message text, PRIMARY KEY(id) );");
// Close connection
pg_close($dbconn);
header('Location: main.php');
die();
?>


