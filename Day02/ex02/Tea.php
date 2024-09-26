<?php

include_once "HotBeverage.php";


class Tea extends HotBeverage{
    private $description = "A green tea.";
    private $comment = "Very good.";

    public function __construct() {
        parent::__construct("Tea", 3, 4.5);
    }

    function getDescription(){
        return $this->description;
    }

    function getComment(){
        return $this->comment;
    }

    
}

?>