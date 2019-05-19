<?php
require_once("map.php");
class player {
    var $name;
    var $room;
    var $count = 0;
    var $x;
    var $y;
    var $z;
    function player($name,$x,$y,$z){
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->room = "(".$this->x.",".$this->y.",".$this->z.")";
        enterRoom($this->name, $this->room);
    }
    function getname() {

        return $this->name;
    }
    function setRoom($x,$y,$z) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->room = "(".$this->x.",".$this->y.",".$this->z.")";

    }
    function getRoom() {
        return $this->room;
    }

}
