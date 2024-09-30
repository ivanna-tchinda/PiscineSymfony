<?php

namespace App\E00Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/e00/firstpage', name: 'e00_home')]
    public function index(): Response
    {
        return new Response('Hello world!');
    }
}

?>