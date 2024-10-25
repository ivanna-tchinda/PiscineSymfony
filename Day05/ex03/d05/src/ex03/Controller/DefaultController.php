<?php

namespace App\ex03\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserFormType;
use Twig\Environment;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    #[Route('/ex03', name: 'home')]
    public function index(Request $request, Environment $twig, UserService $uservice, EntityManagerInterface $entity): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $uservice->index($twig, $user, $entity);
        }
        return new Response($twig->render('form/index.html.twig', [
            'user' => $form->createView(),
        ]));
    }
}