<?php

namespace App\ex13\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmployeeService;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use App\Entity\Employee;
use App\Form\EmployeeType;

class DefaultController extends AbstractController
{
    #[Route('/ex13', name: 'home')]
    public function index(Request $request, Environment $twig, EntityManagerInterface $entity)
    {

        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { // Si le formulaire est soumis et valide
            // Persiste l'employé dans la base de données
            $entity->persist($employee);
            $entity->flush();

            // Ajoute un message de succès et redirige
            $this->addFlash('success', 'Employee created successfully!');

            return $this->redirectToRoute('employee_list');
        }
        return new Response($twig->render('form/index.html.twig', [
            'employee' => $form->createView(),
        ]));
    }

    #[Route('/employees/{id}', name: 'employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->json($employee);
    }

    #[Route('/employees', name: 'employee_list', methods: ['GET'])]
    public function list(Environment $twig, EntityManagerInterface $entityManager): Response
    {
        $employees = $entityManager->getRepository(Employee::class)->findAll();
        $metadata = $entityManager->getClassMetadata(Employee::class);
        $columns = $metadata->getFieldNames();
        return new Response($twig->render('list/index.html.twig', [
            'employees' => $employees,
            'columns' => $columns
        ]));
    }

    #[Route('/employee_create', name: 'employee_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        $employee = new Employee();

        // Populate and validate
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(['error' => (string) $form->getErrors(true)], 400);
        }

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json(['success' => 'Employee created successfully.'], 201);
    }

    #[Route('/update/{id}', name: 'update_id')]
    public function updateId(int $id, Request $request, EmployeeService $employeeservice, EntityManagerInterface $entity, Environment $twig)
    {
        $employee = $entity->getRepository(Employee::class)->find($id);
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $employeeservice->update_employee($twig, $employee, $entity, $id);
        }
        return new Response($twig->render('update_employee/index.html.twig', [
            "employee" => $form->createView(),
        ]));
    }

    #[Route('/delete/{id}', name: 'delete_id')]
    public function deleteId(int $id, EntityManagerInterface $entity, Environment $twig)
    {
        $employee = $entity->getRepository(Employee::class)->find($id);
        $entity->remove($employee);
        $entity->flush();
        return new Response($twig->render('delete_employee/index.html.twig', [
            "id" => $id,
        ]));
    }
}