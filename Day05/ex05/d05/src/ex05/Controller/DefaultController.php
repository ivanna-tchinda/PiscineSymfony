<?php

namespace App\ex05\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use App\Entity\User;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserFormType;

class DefaultController extends AbstractController
{
    #[Route('/ex05', name: 'home')]
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