<?php

namespace App\E05Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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


    #[Route(path: '/form', name: 'app_form')]
    public function form(EntityManagerInterface $entityManager, #[CurrentUser] ?User $user, Request $request): Response
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
            $user->addPost($post);
            $entityManager->persist($post);
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response("form submitted");
        }

        return $this->render('form/index.html.twig', [
            'form' =>  $form
        ]);
    }
    

    
}