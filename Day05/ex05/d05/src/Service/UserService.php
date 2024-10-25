<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
class UserService
{

    #[Route('/form_success', name: 'form_success')]
    public function index(Environment $twig, User $user, EntityManagerInterface $entity): Response
    {
        $repo = $entity->getRepository(User::class)->findAll();
        $entity->persist($user);
        $entity->flush();
        return new Response($twig->render('show_form/index.html.twig', [
            'users' => $repo,
        ]));
    }

}