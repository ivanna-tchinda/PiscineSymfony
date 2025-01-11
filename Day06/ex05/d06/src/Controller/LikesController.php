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
    $app_user = $this->getUser();
    
    if($post->isLikedByUser($app_user)){
      $post->removeLike($app_user);
      $manager->flush();
    }
    else if($post->isDislikedByUser($app_user)){
      $post->addLike($app_user);
      $post->removeDislike($app_user);
      $manager->flush();
    }
    else{
      $post->addLike($app_user);
      $manager->flush();
    }
    return $this->render('message/index.html.twig',[
      'message' => "Post has been liked!"

  ]);
  }

  #[Route(path: '/dislike/post/{id}', name: 'dislike.post')]
  public function dislike(Post $post, EntityManagerInterface $manager, int $id){
    $app_user = $this->getUser();

    if($post->isDislikedByUser($app_user)){
      $post->removeDislike($app_user);
      $manager->flush();
    }

    else if($post->isLikedByUser($app_user)){
      $post->addDislike($app_user);
      $post->removeLike($app_user);
      $manager->flush();
    }
    else{
      $post->addDislike($app_user);
      $manager->flush();
    }
    return $this->render('message/index.html.twig',[
      'message' => "Post has been disliked!"
    ]);
  }
}