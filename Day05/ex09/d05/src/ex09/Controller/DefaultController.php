<?php

namespace App\ex09\Controller;

use App\Entity\Address;
use App\Entity\BankAccount;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use App\Form\PersonFormType;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    #[Route('/ex09', name: 'home')]
    public function index(Request $request, Environment $twig, EntityManagerInterface $entity): Response
    {
        $person = new Person();
        
        // Création du formulaire et gestion de la soumission
        $form = $this->createForm(PersonFormType::class, $person);
        $form->handleRequest($request);
        
        // Récupérer les entités déjà existantes
        $repo = $entity->getRepository(Person::class)->findAll();
        $repo_bank = $entity->getRepository(BankAccount::class)->findAll();
        $repo_address = $entity->getRepository(Address::class)->findAll();
        
        // Récupérer les colonnes des entités pour l'affichage
        $metadata = $entity->getClassMetadata(Person::class);
        $metadata_bank = $entity->getClassMetadata(BankAccount::class);
        $metadata_address = $entity->getClassMetadata(Address::class);
        $columnNames = $metadata->getFieldNames();
        $columnNames_bank = $metadata_bank->getFieldNames();
        $columnNames_address = $metadata_address->getFieldNames();

        // Si le formulaire est soumis et valide, persister les données
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le numéro de compte bancaire de la personne
            $bankAccountNum = $person->getBankAccountNum();
            $address_str = $person->getAddress();

            // Créer le compte bancaire
            $bankAccount = new BankAccount();
            $address = new Address();

            $bankAccount->setBankAccountNum($bankAccountNum);
            $bankAccount->setPerson($person);  // Associer la personne au compte bancaire
            $address->setAddress($address_str);
            $address->setPerson($person);  
            // Ajouter le compte bancaire à la personne (si vous avez une collection, comme un OneToMany)
            $person->setBankAccount($bankAccount);
            $person->setAddressStr($address);

            // Persist les deux entités (l'ordre n'a pas vraiment d'importance ici si vous utilisez une relation bidirectionnelle correcte)
            $entity->persist($person); // Persiste la personne
            $entity->persist($bankAccount); // Persiste le compte bancaire
            $entity->persist($address);

            // Effectuer la mise à jour dans la base de données
            $entity->flush();

            // Récupérer à nouveau la liste des personnes après insertion
            $repo = $entity->getRepository(Person::class)->findAll();
        }

        // Rendu du template avec les données
        return new Response($twig->render('form/index.html.twig', [
            'person' => $form->createView(),
            'persons' => $repo,
            'bank_accounts' => $repo_bank,
            'addresses' => $repo_address,
            'columns' => $columnNames,
            'columns_bank' => $columnNames_bank,
            'columns_address' => $columnNames_address,
        ]));
    }
}
