<?php

namespace App\ex00\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/ex00', name: 'ex00')]
    public function index()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');

        $connection = mysqli_connect($db_server, $db_user, $db_pass);

        if(!$connection){
            return new Response("connection failed" . mysqli_connect_error());
        }
        return new Response("ex00");
    }
}

?>