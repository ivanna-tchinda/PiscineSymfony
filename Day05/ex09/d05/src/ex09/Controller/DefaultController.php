<?php

namespace App\ex09\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use App\Form\PersonFormType;
use Twig\Environment;
use App\Service\PersonService;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    #[Route('/ex09', name: 'home')]
    public function index(Request $request, Environment $twig, PersonService $perservice, EntityManagerInterface $entity): Response
    {

        $person = new Person();

        $form = $this->createForm(PersonFormType::class, $person);
        $form->handleRequest($request);
        $repo = $entity->getRepository(Person::class)->findAll();
        $metadata = $entity->getClassMetadata(Person::class);
        $columnNames = $metadata->getFieldNames();
        if($form->isSubmitted() && $form->isValid()){
          $entity->persist($person);
          $entity->flush();
          $repo = $entity->getRepository(Person::class)->findAll();
        }
        return new Response($twig->render('form/index.html.twig', [
            'person' => $form->createView(),
            'persons' => $repo,
            'columns' => $columnNames
        ]));
    }
}