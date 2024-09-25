<?php

include "TemplateEngine.php";

$template = new TemplateEngine();
$fileName = "template.html";
$templateName = "book_description.html";

$nom = "nom";
$auteur = "auteur";
$description = "description";
$prix = "prix";

$parameters = [$nom, $auteur, $description, $prix];

$template->createFile($fileName, $templateName, $parameters);
?>