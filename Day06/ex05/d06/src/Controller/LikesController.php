<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Post;

class LikesController extends AbstractController
{
  #[Route(path: '/like/post/{id}', name: 'like.post')]
  public function like(Post $post, EntityManagerInterface $manager, int $id){
    $user = $this->getUser();
    $repo = $manager->getRepository(Post::class)->findBy([], ['created' => 'DESC']);

    if($post->isLikedByUser($user)){
      $post->removeLike($user);
      $manager->flush();
    }
    else if($post->isDislikedByUser($user)){
      $post->addLike($user);
      $post->removeDislike($user);
      $manager->flush();
    }
    else{
      $post->addLike($user);
      $manager->flush();
    }
    return $this->render('home/index.html.twig',[
      'posts' => $repo
  ]);
  }

  #[Route(path: '/dislike/post/{id}', name: 'dislike.post')]
  public function dislike(Post $post, EntityManagerInterface $manager, int $id){
    $user = $this->getUser();
    $repo = $manager->getRepository(Post::class)->findBy([], ['created' => 'DESC']);

    if($post->isDislikedByUser($user)){
      $post->removeDislike($user);
      $manager->flush();
    }

    else if($post->isLikedByUser($user)){
      $post->addDislike($user);
      $post->removeLike($user);
      $manager->flush();
    }
    else{
      $post->addDislike($user);
      $manager->flush();
    }
    return $this->render('home/index.html.twig',[
            'posts' => $repo
        ]);
  }
}