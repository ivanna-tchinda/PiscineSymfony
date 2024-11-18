<?php

namespace App\ex13\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class DefaultController extends AbstractController
{
    #[Route('/ex13', name: 'home')]
    public function index(Environment $twig, EntityManagerInterface $entity)
    {
        return new Response("ex13");
    }
}