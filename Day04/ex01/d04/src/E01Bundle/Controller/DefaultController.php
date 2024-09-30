<?php

namespace App\E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/e01/', name: 'homepage')]
    public function index(): Response
    {
        $myName = 'Ivanna'; 
        return $this->render('base.html.twig', [
            'name' => $myName
        ]);
    }

    #[Route('/e01/{article}', name: 'e01_yes')]
    public function index2(string $article): Response
    {
        return new Response("$article");
    }
}

?>