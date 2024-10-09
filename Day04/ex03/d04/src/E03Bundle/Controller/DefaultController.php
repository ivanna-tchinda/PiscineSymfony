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
        $bytes1 = random_bytes(1);
        $bytes2 = random_bytes(2);
        return new Response($twig->render('table/index.html.twig', [
            'nb_of_colors' => $this->getParameter('e03.number_of_colors'),
            'black' => random_int(0, 127),
            'red' => random_int(0, 127),
            'blue' => random_int(0, 127),
            'green' => random_int(0, 127),
        ]));
    }
}

?>