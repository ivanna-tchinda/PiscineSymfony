<?php

namespace App\ex06\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use mysqli_sql_exception;

class DefaultController extends AbstractController
{
    #[Route('/ex06', name: 'home')]
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

    #[Route('/success_update', name: 'success_update')]
    public function updateUser(Environment $twig): Response
    {
        return $this->updateTable($_POST, $twig);
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
        $db_name = "ex06";

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

    #[Route('/update_{id}', name: 'update_id')]
    public function updateId(int $id, Environment $twig) 
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex06";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }

        $result = $connection->query("SELECT * FROM users WHERE id=$id");
        if(!$result)
            return new Response("Error: table has no user with id $id");
        $user = $result->fetch_all()[0];
        return new Response($twig->render('update_user/index.html.twig', [
            'id' => $user[0],
            'username' => $user[1],
            'name' => $user[2],
            'mail' => $user[3],
            'enable' => $user[4],
            'birthdate' => $user[5],
            'address' => $user[6],
        ]));
    }


    #[Route('/delete/{id}', name: 'delete_id')]
    public function delete_id(int $id, Environment $twig): Response
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex06";

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

    public function updateTable($data, Environment $twig): Response
    {
        $id = $data["id"];
        $username = $data['username'];
        $name = $data['name'];
        $email = $data['email'];
        $enable = $data['enable'];
        $birthdate = $data['birthdate'];
        $address = $data['address'];

        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex06";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
            $sql = "UPDATE users 
            SET username='$username', _name='$name', email='$email', birthdate='$birthdate', _enable=$enable, _address='$address' 
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
        $address = $data['address'];

        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex06";

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
        $db_name = "ex06";

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
        $db_name = "ex06";

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