<?php

namespace App\ex04\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route('/ex04', name: 'home')]
    public function index(Environment $twig): Response
    {
        $this->create_database();
        $this->create_table();
        return new Response($twig->render('form/index.html.twig'));
    }

    #[Route('/success_insert', name: 'success_insert')]
    public function insertForm(Environment $twig): Response
    {
        return $this->insertTable($_POST, $twig);
    }

    #[Route('/show_form', name: 'show_form')]
    public function showForm(Environment $twig): Response
    {
        return $this->getTable($twig);
    }

    public function getTable(Environment $twig)
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex04";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }

        $sql = "SELECT * FROM users";
        if ($result = $connection->query($sql)) {
            $connection->close();
            return new Response($twig->render('show_form/index.html.twig', [
                'users' => $result,
            ]));

        } else {
            return new Response("Error getting infos from table: " . $connection->error);
        }
    }

    #[Route('/delete/{id}', name: 'delete_id')]
    public function delete_id(int $id, Environment $twig): Response
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex04";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }

        if($result = $connection->query("SELECT * FROM users WHERE id=$id")){
            if ($connection->query("DELETE FROM users WHERE id=$id") === TRUE && $result->num_rows) {
                $connection->close();
                return new Response($twig->render('success_delete/index.html.twig', [
                    'id' => $id
                ]));
    
            }

        }
        return new Response("Id $id is not in the table and cannot be removed");
    }

    public function insertTable($data, Environment $twig): Response
    {
        $username = $data['username'];
        $name = $data['name'];
        $email = $data['email'];
        $enable = $data['enable'];
        $birthdate = $data['birthdate'];
        $address = $data['address'];

        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex04";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
            $sql = "
            INSERT INTO users (username, _name, email, _enable, birthdate, _address)
            VALUES ('$username', '$name', '$email', $enable, '$birthdate', '$address');";
    
            if ($connection->query($sql) === TRUE) {
                $connection->close();
                echo "Successfully added in table!";
                return new Response($twig->render('success_insert/index.html.twig'));

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
        $db_name = "ex04";

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

    public function create_table()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex04";

        try{
        $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            echo "Connection failed: Database doesn't exist";
        }
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            _name VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            _enable BOOLEAN,
            birthdate DATE,
            _address LONGTEXT
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "Table 'Users' created successfully.\n";
        } else {
            $connection->close();
            echo "Error creating table: " . $connection->error;
        }
    }
}