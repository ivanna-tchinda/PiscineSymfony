<?php

namespace App\E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/e01/', name: 'e01_homePage')]
    public function index(): Response
    {
        return $this->render('homePage/index.html.twig');
    }

    #[Route('/e01/{article}', name: 'e01_article')]
    public function index2(string $article): Response
    {
        $articles = ["bluewhale", "goeland", "tiger"];
        $view = "homePage/index.html.twig";

        if(in_array($article, $articles)){
            $view = "articles/$article/index.html.twig";
        }
        return $this->render($view);
    }
}

?>