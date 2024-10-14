<?php

namespace App\ex01\Controller;

use Twig\Environment;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/ex01', name: 'home')]
    public function index(Environment $twig, Request $request)
    {
        return new Response($twig->render('table/index.html.twig'));
    }


    #[Route('/create_table', name: 'create_table')]
    public function createTable(EntityManagerInterface $entityManager)
    {
        $user_table = $entityManager->getRepository(Users::class);
        if(!$user_table){
            return new Response("Error with creation of table");
        }
        return new Response("Table has been created");
    }
}

?>