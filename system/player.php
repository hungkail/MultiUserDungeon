<?php
require_once("map.php");
/**
 * A Class to simply hold player data locally
 */
class player {
    var $name;
    var $room;
    var $count = 0;
    var $x;
    var $y;
    var $z;

    /**
     * player constructor.
     * @param $name        player input name
     * @param $x           player location param for x-coordinate
     * @param $y           player location param for y-coordinate
     * @param $z           player location param for z-coordinate
     */
    function player($name,$x,$y,$z){
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->room = "(".$this->x.",".$this->y.",".$this->z.")";
        enterRoom($this->name, $this->room);
    }

    /**
     * @return string Player name
     */
    function getname() {

        return $this->name;
    }

    /**
     * @param $x    new player location at x-coordinate
     * @param $y    new player location at y-coordinate
     * @param $z    new player location at z-coordinate
     */
    function setRoom($x,$y,$z) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->room = "(".$this->x.",".$this->y.",".$this->z.")";

    }

    /**
     * @return string the room player inside
     */
    function getRoom() {
        return $this->room;
    }

}
