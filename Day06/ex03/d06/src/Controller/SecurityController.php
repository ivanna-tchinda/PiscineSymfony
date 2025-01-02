<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/form', name: 'app_form')]
    public function admin(EntityManagerInterface $entityManager, #[CurrentUser] ?User $user, Request $request): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        // ou ajouter un message optionnel - visible pour les développeurs
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, 'User tried to access a page without being authenticated');
        
        $post = new Post();

        $now = date("Y-m-d h:i:sa");
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create post'])
            ->getForm();

        $post->setCreated($now);
        $post->setAuthor($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
            $task = $form->getData();
    
                // ... perform some action, such as saving the task to the database
    
            $entityManager->persist($post);
            $entityManager->flush();
            return new Response("form submitted");
        }

        return $this->render('form/index.html.twig', [
            'form' =>  $form
        ]);
    }

    #[Route(path: '/post/{id}', name: 'app_form')]
    public function post_id(EntityManagerInterface $entityManager, int $id): Response
    {
        $post = $entityManager->getRepository(Post::class)->findOneBy(['id' => $id]);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        // ou ajouter un message optionnel - visible pour les développeurs
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, 'User tried to access a page without being authenticated');
        
        return $this->render('show_post/index.html.twig', [
            'post' =>  $post,
            'id' => $id
        ]);
    }


}