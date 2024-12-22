<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

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

    #[Route(path: '/admin', name: 'app_admin')]
    public function admin(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // ou ajouter un message optionnel - visible pour les développeurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route(path: '/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils, int $id)
    {
        $user = $this->getUser();
        $repository = $entityManager->getRepository(User::class);
        $product = $repository->find($id);
        $message = "You cannot delete yourself";
        if($user->getId() != $id)
        {
            $message = "User $id has been deleted.";
            $entityManager->remove($product);
            $entityManager->flush();
        }
        return $this->render('delete/index.html.twig', [
            'message' => $message,
        ]);
    }


}