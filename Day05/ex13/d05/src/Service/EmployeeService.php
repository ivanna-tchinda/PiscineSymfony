<?php
namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
class EmployeeService
{

    #[Route('/form_success', name: 'form_success')]
    public function index(Environment $twig, Employee $employee, EntityManagerInterface $entity): Response
    {
        $entity->persist($employee);
        $entity->flush();
        $repo = $entity->getRepository(Employee::class)->findAll();
        return new Response($twig->render('show_form/index.html.twig', [
            'employees' => $repo,
        ]));
    }

    #[Route('/update_success', name: 'update_success')]
    public function update_employee(Environment $twig, Employee $employee, EntityManagerInterface $entity, int $id): Response
    {
        $entity->persist($employee);
        $entity->flush();
        // $repo = $entity->getRepository(User::class)->find($id);
        return new Response($twig->render('success_update/index.html.twig', [
            'id' => $id,
        ]));
    }

}