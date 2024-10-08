<?php

namespace App\E03Bundle\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/e03', name: 'e03_home')]
    public function index(Environment $twig, Request $request)
    {
        return new Response($twig->render('table/index.html.twig', [
            'nb_of_colors' => $this->getParameter('e03.number_of_colors'),
            'color1' => '#000000' . bin2hex(random_bytes(1)),
            'color2' => '#FF0000' . bin2hex(random_bytes(1)),
            'color3' => '#0000FF' . bin2hex(random_bytes(1)),
            'color4' => '#00ff00' . bin2hex(random_bytes(1)),
        ]));
    }
}

?>