<?php
namespace App\Service;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
class PersonService
{

    #[Route('/form_success', name: 'form_success')]
    public function index(Environment $twig, Person $person, EntityManagerInterface $entity): Response
    {
    }

}