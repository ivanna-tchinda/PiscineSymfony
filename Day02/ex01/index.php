<?php

include "./Text.php";
include "./TemplateEngine.php";

$tab_str = array("chaine1", "chaine2");
$text = new Text($tab_str);
$fileName = "template.html";

$text->append("chaine3", "chaine4", "chaine5", "...");

$temp = new TemplateEngine();
$temp->createFile($fileName, $text);
?>