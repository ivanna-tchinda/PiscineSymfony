<?php

class HotBeverage{
    public $name = null;
    public $price = null;
    public $resistance = null;

    public function __construct($name="null", $price=null, $resistance=null){
        $this->name = $name;
        $this->price = $price;
        $this->resistance = $resistance;
    }

    function getName(){
        return $this->name;
    }

    function getPrice(){
        return $this->price;
    }

    function getResistance(){
        return $this->resistance;
    }
}

?>