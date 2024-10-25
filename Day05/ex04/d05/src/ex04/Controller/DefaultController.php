<?php

namespace App\ex04\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    #[Route('/ex04', name: 'home')]
    public function index(): Response
    {
        $this->create_table();
        return new Response('ex04');
    }

    public function create_table()
    {
        
    }
}