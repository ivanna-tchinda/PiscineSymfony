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

    #[Route('/show_form', name: 'show_form')]
    public function show_form(Environment $twig, EntityManagerInterface $entity)
    {
        $repo = $entity->getRepository(User::class)->findAll();
        return new Response($twig->render('show_form/index.html.twig', [
            'users' => $repo,
        ]));
    }

    #[Route('/delete/{id}', name: 'delete_id')]
    public function deleteId(int $id, EntityManagerInterface $entity, Environment $twig)
    {
        $user = $entity->getRepository(User::class)->find($id);
        $entity->remove($user);
        $entity->flush();
        return new Response($twig->render('delete_user/index.html.twig', [
            "id" => $id,
        ]));
    }
    

}