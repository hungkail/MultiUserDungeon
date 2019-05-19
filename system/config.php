<?php
    /** dungeon parameters
     *  can change dungeon size by modify Length, Width, and Height
     */
    $dungeonLength = 3;
    $dungeonWidth = 3;
    $dungeonHeight = 3;
    /** Database Path
     *  Connecting to Heroku postgres database
     */
    $conn_string = getenv("DATABASE_URL");
?>
