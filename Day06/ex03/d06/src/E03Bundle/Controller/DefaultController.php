<?php

namespace App\E03Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route(path:'/home', name: 'home')]
    public function index()
    {
        return $this->render('posts/index.html.twig');

    }

    #[Route(name: 'add_post')]
    public function add_post()
    {
        return $this->render('posts/index.html.twig');

    }
}