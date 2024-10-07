<?php

namespace App\E02Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/e03', name: 'e03_home')]
    public function index(): Response
    {
        return new Response('Hello woddrlddd!');
    }
}

?>