<?php

namespace App\E02Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route('/ex02', name: 'home')]
    public function index()
    {
        return new Response("ex02");
    }
}