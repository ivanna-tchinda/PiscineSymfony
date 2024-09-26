<?php

include_once "TemplateEngine.php";
include_once "Coffee.php";
include_once "Tea.php";

$template = new TemplateEngine();
$fileName = "file.html";

$coffee = new Coffee();
$tea = new Tea();

$template->createFile($fileName, $coffee);
$template->createFile($fileName, $tea);
?>