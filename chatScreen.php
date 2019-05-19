<?php
session_start();
/** refresh page every 2 second
 *  in order to show current chat message received
 */
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

