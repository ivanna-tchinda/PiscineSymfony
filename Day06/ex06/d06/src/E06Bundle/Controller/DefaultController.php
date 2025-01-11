<?php

namespace App\E06Bundle\Controller;

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
use Symfony\Component\Security\Core\Security;
use App\Form\PostType;

class DefaultController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/home', name: 'home')]
    public function index(Environment $twig, EntityManagerInterface $entity)
    {
        $app_user = $this->security->getUser();
        $repo = $entity->getRepository(Post::class)->findBy([], ['created' => 'DESC']);
        $appuser_like_number = 0;
        $appuser_dislike_number = 0;
        foreach ($repo as $key => $value) {
            if($value->getAuthor() != $app_user)
                continue;
            $appuser_like_number += count($value->getLikes());
            $appuser_dislike_number += count($value->getDislikes());
        }
        return new Response($twig->render('home/index.html.twig',[
            'posts' => $repo,
            'appuser_like_number' => $appuser_like_number,
            'appuser_dislike_number' => $appuser_dislike_number,
        ]));
    }


    #[Route(path: '/form', name: 'app_form')]
    public function form(EntityManagerInterface $entityManager, #[CurrentUser] ?User $user, Request $request): Response
    {
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

        $form->handleRequest($request);

        $post->setCreated($now);
        $post->setAuthor($user);
        $post->setLastUpdateUser($user);
        $post->setLastUpdateTime($now);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
    
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
    
    #[Route('/edit_post/{id}', name: 'edit_post')]
    public function edit_post(EntityManagerInterface $entity, #[CurrentUser] ?User $user, int $id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, 'User tried to access a page without being authenticated');
        
        $now = date("Y-m-d h:i:sa");
        
        $post = $entity->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }

        $post->setLastUpdateUser($user);
        $post->setLastUpdateTime($now);
        $form = $this->createForm(PostType::class, $post);


        $form->handleRequest($request);

        // $now = date("Y-m-d h:i:sa");
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entity->persist($post);
            $entity->flush();
            return new Response("form submitted");
        }
        return $this->render('edit_post/index.html.twig',[
            'form' =>  $form
        ]);
    }

    
}