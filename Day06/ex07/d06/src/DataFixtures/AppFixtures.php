<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@gmail.com');
            $password = $this->hasher->hashPassword($user, 'pass_1234');
            $user->setPassword($password);
            $user->setReputationNum($i);
            if($user->getReputationNum() > 8)
                $user->setRoles(['ROLE_GOLD']);
            else if($user->getReputationNum() > 5)
                $user->setRoles(['ROLE_PREMIUM']);
            else if($user->getReputationNum() > 2)
                $user->setRoles(['ROLE_STANDARD']);
            else
                $user->setRoles(['ROLE_BASIC']);

            $now = date("Y-m-d h:i:sa");
            $post = new Post();
            $post->setTitle('title_'.$i);
            $post->setContent('content_'.$i);
            $post->setCreated($now);
            $post->setAuthor($user);
            $post->setLastUpdateUser($user);
            $post->setLastUpdateTime($now);

            $manager->persist($user);
            $manager->persist($post);
        }

        $manager->flush();
    }
}
