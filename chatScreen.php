<?php
session_start();
?>

<meta http-equiv="refresh" content="2">
<link type="text/css" rel="stylesheet" href="styles.css" />
<html>
<h2>Chat</h2>
</html>
<?php
    require_once("./system/map.php");

    echo displayMessage($_SESSION['user_name']);
    ?>

