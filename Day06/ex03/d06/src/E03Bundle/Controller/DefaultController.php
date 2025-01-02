<?php

namespace App\E03Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Entity\Post;

class DefaultController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Environment $twig, EntityManagerInterface $entity)
    {
        $repo = $entity->getRepository(Post::class)->findBy([], ['created' => 'DESC']);
        return new Response($twig->render('home/index.html.twig',[
            'posts' => $repo
        ]));
    }

    #[Route('/form', name: 'form')]
    public function form()
    {
        //display all the posts
        return new Response("<a href='/login'>Log in</a> to access this page");
    }

    
}