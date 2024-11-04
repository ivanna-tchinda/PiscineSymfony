<?php

namespace App\ex08\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route('/ex08', name: 'home')]
    public function index(Environment $twig): Response
    {
        $this->create_database();
        $table = $this->check_table();
        $persons = $this->getTable($twig);
        $bank_accounts = $this->get_bank();
        $addresses = $this->get_address();
        return new Response($twig->render('home/index.html.twig', [
            'table' => $table,
            'persons' => $persons,
            'bank_accounts' => $bank_accounts,
            'addresses' => $addresses
        ]));
    }

    #[Route('/success_insert', name: 'success_insert')]
    public function insertForm(Environment $twig): Response
    {
        return $this->insertTable($_POST, $twig);
    }

    #[Route('/success_update', name: 'success_update')]
    public function updatePerson(Environment $twig): Response
    {
        return $this->updateTable($_POST, $twig);
    }

    #[Route('/show_form', name: 'show_form')]
    public function showForm(Environment $twig): Response
    {
        return $this->getTable($twig);
    }

    public function create_connection()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex08";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
        return $connection;
    }

    public function check_table()
    {
        $connection = $this->create_connection();
        $sql = "SELECT * FROM persons";
        if ($result = $connection->query($sql)) 
            return 1;
        return 0;
    }

    public function getTable(Environment $twig)
    {
        $connection = $this->create_connection();

        $sql = "SELECT * FROM persons";
        if ($result = $connection->query($sql)) {
            $connection->close();
            return $result;

        } else {
            return new Response("Error getting infos from table: " . $connection->error);
        }
    }

    #[Route('/update_{id}', name: 'update_id')]
    public function updateId(int $id, Environment $twig) 
    {
        $connection = $this->create_connection();

        $result = $connection->query("SELECT * FROM persons WHERE id=$id");
        if(!$result)
            return new Response("Error: table has no person with id $id");
        $person = $result->fetch_all()[0];
        return new Response($twig->render('update_person/index.html.twig', [
            'id' => $person[0],
            'username' => $person[1],
            'name' => $person[2],
            'mail' => $person[3],
            'enable' => $person[4],
            'birthdate' => $person[5],
        ]));
    }


    #[Route('/delete/{id}', name: 'delete_id')]
    public function delete_id(int $id, Environment $twig): Response
    {
        $connection = $this->create_connection();

        if($result = $connection->query("SELECT * FROM persons WHERE id=$id")){
            if ($connection->query("DELETE FROM persons WHERE id=$id") === TRUE && $result->num_rows) {
                $connection->close();
                return new Response($twig->render('success_delete/index.html.twig', [
                    'id' => $id
                ]));
    
            }

        }
        return new Response("Id $id is not in the table and cannot be removed");
    }

    public function updateTable($data, Environment $twig): Response
    {
        $id = $data["id"];
        $username = $data['username'];
        $name = $data['name'];
        $email = $data['email'];
        $enable = $data['enable'];
        $birthdate = $data['birthdate'];

        $connection = $this->create_connection();

        $sql = "UPDATE persons 
        SET username='$username', _name='$name', email='$email', birthdate='$birthdate', _enable=$enable 
        WHERE id=$id;";
    
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response($twig->render('success_update/index.html.twig', [
                "id" => $id,
            ]));

        } else {
            $connection->close();
            echo "Error inserting in table: " . $connection->error;
        }
    }

    public function insertTable($data, Environment $twig): Response
    {
        $username = $data['username'];
        $name = $data['name'];
        $email = $data['email'];
        $enable = $data['enable'];
        $birthdate = $data['birthdate'];

        $connection = $this->create_connection();
        $sql = "
        INSERT INTO persons (username, _name, email, _enable, birthdate)
        VALUES ('$username', '$name', '$email', $enable, '$birthdate');";
    
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response($twig->render('success_insert/index.html.twig', [
                "username" => $username,
            ]));

        } else {
            $connection->close();
            echo "Error inserting in table: " . $connection->error;
        }
    }

    public function create_database()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex08";

        // Create connection to MySQL server
        $connection = new \mysqli($db_server, $db_user, $db_pass);

        if ($connection->connect_error) {
            echo "Connection failed: " . $connection->connect_error;
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "Database '$db_name' created successfully.\n";
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
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            _name VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            _enable BOOLEAN,
            birthdate DATE
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table 'Persons' created successfully.\n");
        } else {
            $connection->close();
            echo "Error creating table: " . $connection->error;
        }
    }
}