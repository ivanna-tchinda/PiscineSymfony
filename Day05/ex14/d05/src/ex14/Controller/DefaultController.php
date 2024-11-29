<?php

namespace App\ex14\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route('/ex14', name: 'home')]
    public function index(Environment $twig): Response
    {
      $this->create_database();
      $persons = $this->getTable();
      $table = $this->check_table();

      $columns_persons = [];
      while ($persons && $col = mysqli_fetch_field($persons))
          array_push($columns_persons, $col->name);

      return new Response($twig->render('home/index.html.twig', [
        'table' => $table,
        'persons' => $persons,
        'columns' => $columns_persons
    ]));
    }

    public function getTable()
    {
        $connection = $this->create_connection();

        $sql = "SELECT * FROM persons";
        try{
            $result = $connection->query($sql);
            $connection->close();
            return $result;

        }  catch (mysqli_sql_exception $e) {
            return 0;
        }
    }

    public function create_connection()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex14";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
        return $connection;
    }

    #[Route('/success_insert', name: 'success_insert')]
    public function insertForm(Environment $twig): Response
    {
        return $this->insertTable($_POST, $twig);
    }

    public function insertTable($data, Environment $twig): Response
{
    $connection = $this->create_connection();

    $username = $data['username'];
    $name = $data['_name'];
    $email = $data['email'];
    $birthdate = $data['birthdate'];
    var_dump($data);
    
    // Construction de la requête SQL avec injection de données non échappées
    $sql = "
    INSERT INTO persons (username, _name, email, birthdate)
    VALUES ('$username', '$name', '$email', '$birthdate'
    );";

    try {
        // Exécution de la requête SQL
        $connection->multi_query($sql);

        // Fermeture de la connexion
        $connection->close();

        // Affichage de la vue de succès
        return new Response($twig->render('success_insert/index.html.twig', [
            'username' => $data['username']
        ]));

    } catch (mysqli_sql_exception $e) {
        // Gestion des erreurs
        return new Response("Error inserting in table persons: " . $e->getMessage() . "<br>");
    }
}





    public function create_database()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex14";

        // Create connection to MySQL server
        $connection = new \mysqli($db_server, $db_user, $db_pass);

        if ($connection->connect_error) {
            echo "Connection failed: " . $connection->connect_error;
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "Database '$db_name' created successfully.<br>";
        } else {
            $connection->close();
            echo "Error creating database: " . $connection->error;
        }
    }

    #[Route('/create_table', name: 'create_table')]
    public function create_table()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS persons (
            person_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(250) NOT NULL,
            _name VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            birthdate DATE
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table 'Persons' created successfully.<a href='/ex14'>Back</a><br>");
        } else {
            $connection->close();
            echo "Error creating table persons: " . $connection->error . "<br>";
        }
    }

    public function check_table()
    {
        $connection = $this->create_connection();
        $sql = "SELECT * FROM persons";
        try{
            $result = $connection->query($sql);
            return 1;
        } catch (mysqli_sql_exception $e){
            return 0;
        }
    }
}