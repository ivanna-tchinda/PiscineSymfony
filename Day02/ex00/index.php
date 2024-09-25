<?php

include "TemplateEngine.php";

$template = new TemplateEngine();
$fileName = "template.html";
$templateName = "book_description.html";

$nom = "tc";
$auteur = "iv";
$description = "yes";
$prix = "00";

$parameters = ["nom" => $nom, "auteur" => $auteur, "description" => $description, "prix" => $prix];

$template->createFile($fileName, $templateName, $parameters);
?>