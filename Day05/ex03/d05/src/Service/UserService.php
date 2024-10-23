<?php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class UserService
{

#[Route('/form_success', name: 'form_success')]
    public function index(Environment $twig): Response
    {
        return $this->render('show_form/index.html.twig');
    }

}