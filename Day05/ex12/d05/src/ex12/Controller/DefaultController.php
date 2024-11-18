<?php

namespace App\ex12\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use App\Entity\Informations;
use App\Entity\Email;

class DefaultController extends AbstractController
{
    #[Route('/ex12', name: 'home')]
    public function index(Environment $twig, EntityManagerInterface $entity)
    {
        $this->create_database();
        $filesystem = new Filesystem();
        
        $list_values = $this->get_values_file();
        $this->add_orm($list_values, $entity);

        $repo = $entity->getRepository(Informations::class)->findAll();
        $metadata = $entity->getClassMetadata(Informations::class);
        $columnNames = $metadata->getFieldNames();


        $emails = $entity->getRepository(Email::class)->findAll();
        $metadata_email = $entity->getClassMetadata(Email::class);
        $columns_email = $metadata_email->getFieldNames();

        return new Response($twig->render('tables/index.html.twig', [
            'informations' => $repo,
            'columns' => $columnNames,
            'emails' => $emails,
            'columns_email' => $columns_email,
        ]));
    }

    public function filter_table(Environment $twig, EntityManagerInterface $entity, string $filter, string $filter_value, string $sort_type)
    {
        echo "$filter, $filter_value, $sort_type<br>";
        
        // Déterminer le type de tri
        $sort = ($sort_type == 'increase') ? 'ASC' : 'DESC';
    
        // Créer le QueryBuilder
        $qb = $entity->createQueryBuilder();
    
        // Sélectionner les colonnes nécessaires
        $qb->select('informations', 'emails')
           ->from(Informations::class, 'informations')
           ->leftJoin('informations.email', 'emails');
    
        // Appliquer le filtre dynamique selon le champ demandé
        if (in_array($filter, ['username', 'name', 'age'])) {
            // Si c'est un champ valide (ex: username, name, age)
            $qb->where("informations.$filter = :$filter");
        } elseif ($filter == 'email') {
            // Si on filtre sur l'email de l'entité Email
            $qb->where("emails.email = :email");
        } else {
            throw new \Exception('Filtre non valide');
        }
    
        // Lier la valeur du paramètre de filtre
        $qb->setParameter($filter, $filter_value);
    
        // Appliquer l'ordre de tri
        $qb->orderBy('informations.id', $sort);
    
        // Exécuter la requête
        $query = $qb->getQuery();
        $result = $query->getResult();
    
        return $result;
    }
    


    #[Route('/success_filter', name: 'success_filter')]
    public function success_filter(Environment $twig, EntityManagerInterface $entity)
    {
      $filter = $_POST["filter"];
      $filter_value = $_POST["filter_value"];
      $sort_type = $_POST["sort"];
      $filtered_table = $this->filter_table($twig, $entity, $filter, $filter_value, $sort_type);
      return new Response($twig->render('filter/index.html.twig', [
        'emails' => $filtered_table,
      ]));
    }

    public function add_orm(Array $list_values, EntityManagerInterface $entity)
    {
        $infos = new Informations();
        $email = new Email();

        $infos->setUsername($list_values[0]);
        $infos->setName($list_values[1]);
        $infos->setAge($list_values[2]);
        $email->setEmail($list_values[3]);
        $infos->setEmail($email);

        $entity->persist($infos); 
        $entity->persist($email); 
        $entity->flush();
    }

    public function get_values_file()
    {
        $content = file_get_contents('file.txt');
        $elts = explode("\n", $content);
        $list_values = [];
        array_push($list_values, explode(":", $elts[0])[1]);
        array_push($list_values, explode(":", $elts[1])[1]);
        array_push($list_values, explode(":", $elts[2])[1]);
        array_push($list_values, explode(":", $elts[3])[1]);
        return $list_values;
    }


    public function create_database()
    {
        // Create connection to MySQL server
        $connection = $this->create_connection();

        if ($connection->connect_error) {
            return new Response("Connection failed: $connection->connect_error" );
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS ex12;";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Database 'ex12' and table sql_informations created successfully.<br>");
        } else {
            $connection->close();
            return new Response("Error creating database: $connection->error");
        }
    }

    public function create_connection()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex12";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
        return $connection;
    }
}
