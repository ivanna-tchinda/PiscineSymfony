<?php

include_once "Elem.php";
include_once "TemplateEngine.php";

$elem = new Elem('html');
$body = new Elem('body');
$body->pushElement(new Elem('p', 'Lorem ipsum', ['class' => 'text-muted', "style" => "color:blue"]));
$elem->pushElement($body);
echo $elem->getHTML();

$fileName = "file.html";
$tmp = new TemplateEngine($elem);
$tmp->createFile($fileName);

$elem = new Elem('undefined'); 


?>