<?php

include_once "HotBeverage.php";

class Coffee extends HotBeverage{
    private string $description = "A black coffee.";
    private string $comment = "Tasty.";

    public function __construct() {
        parent::__construct("Coffee", 4, 5);
    }

    function getDescription(){
        return $this->description;
    }

    function getComment(){
        return $this->comment;
    }
}

?>