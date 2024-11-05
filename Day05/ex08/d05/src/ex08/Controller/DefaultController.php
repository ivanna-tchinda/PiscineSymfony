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
        // $bank_accounts = $this->get_bank();
        // $addresses = $this->get_address();
        $columns = [];
        if($persons)
        while ($col = mysqli_fetch_field($persons))
            array_push($columns, $col->name);
        return new Response($twig->render('home/index.html.twig', [
            'table' => $table,
            'persons' => $persons,
            'columns' => $columns,
            // 'bank_accounts' => $bank_accounts,
            // 'addresses' => $addresses
        ]));
    }
    #[Route('/add_column', name: 'add_column')]
    public function add_column(Environment $twig)
    {
        return new Response($twig->render('add_column/index.html.twig'));
    }

    #[Route('/add_column_success', name: 'add_column_success')]
    public function add_column_success(Environment $twig)
    {
        $column_name = $_POST["column_name"];
        $column_type = $_POST["column_type"];
        $connection = $this->create_connection();

        $res = mysqli_query($connection, 'SELECT * FROM persons');
        while ($property = mysqli_fetch_field($res))
            $name = $property->name;
        $sql = "
        ALTER TABLE persons ADD $column_name $column_type after $name";
        try {
            $result = $connection->query($sql);
            $connection->close();
            return new Response($twig->render('success_add_column/index.html.twig', [
                'column_name' => $column_name
            ]));
        } catch (mysqli_sql_exception $e) {
            $connection->close();
            return new Response("Connection failed: ERROR WHILE CREATING COLUMN");
        }
    }

    public function get_address()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS addresses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            FOREIGN KEY(_name) REFERENCES persons(_name)
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table Addresses created successfully.\n");
        } else {
            $connection->close();
            echo "Error creating table: " . $connection->error;
        }
    }

    public function get_bank()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS bank_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ADD CONSTRAINT name_account,
            FOREIGN KEY(_name) REFERENCES persons(_name)
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table Bank_accounts created successfully.\n");
        } else {
            $connection->close();
            echo "Error creating table: " . $connection->error;
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
    public function showForm(Environment $twig)
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
        try{
            $result = $connection->query($sql);
            return 1;
        } catch (mysqli_sql_exception $e){
            return 0;
        }
    }

    public function getTable(Environment $twig)
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
        $name = $data['_name'];
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

}