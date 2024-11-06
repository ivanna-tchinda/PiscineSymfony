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
        $persons = $this->getTable();
        $bank_accounts = $this->get_bank();
        $addresses = $this->get_address();
        $columns_persons = [];
        $columns_bank = [];
        $columns_address = [];

        while ($persons && $col = mysqli_fetch_field($persons))
            array_push($columns_persons, $col->name);
        while ($bank_accounts && $col = mysqli_fetch_field($bank_accounts))
            array_push($columns_bank, $col->name);
        while ($addresses && $col = mysqli_fetch_field($addresses))
            array_push($columns_address, $col->name);

        return new Response($twig->render('home/index.html.twig', [
            'table' => $table,
            'persons' => $persons,
            'bank_accounts' => $bank_accounts,
            'addresses' => $addresses,
            'columns' => $columns_persons,
            'columns_bank' => $columns_bank,
            'columns_addresses' => $columns_address
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

    public function create_bank()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS bank_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bank_account_id INT(6),
            FOREIGN KEY (bank_account_id) REFERENCES persons(bank_account_id)
        )";

        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table Bank_accounts created successfully.\n");
        } else {
            $connection->close();
            echo "Error creating table bank_accounts: " . $connection->error . "<br>";
        }
    }

    public function get_bank()
    {
        $this->create_bank();
        $connection = $this->create_connection();

        $sql = "SELECT * FROM bank_accounts";
        try{
            $result = $connection->query($sql);
            $connection->close();
            return $result;

        }  catch (mysqli_sql_exception $e) {
            return 0;
        }
    }

    public function create_address()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS addresses (
            addressID INT AUTO_INCREMENT PRIMARY KEY,
            address VARCHAR(255),
            person_id INT(6) UNSIGNED,
            FOREIGN KEY (person_id) REFERENCES persons(person_id)
        )";

        try{
            $connection->query($sql) === TRUE;
            $connection->close();
            return new Response("Table Bank_accounts created successfully.\n");
        } catch (mysqli_sql_exception $e) {
            echo "Error creating table addresses: " . $connection->error . "<br>";
        }
    }

    public function get_address()
    {
        $this->create_address();
        $connection = $this->create_connection();

        $sql = "SELECT * FROM addresses";
        try{
            $result = $connection->query($sql);
            $connection->close();
            return $result;

        }  catch (mysqli_sql_exception $e) {
            return 0;
        }
    }

    #[Route('/create_table', name: 'create_table')]
    public function create_table()
    {
        $connection = $this->create_connection();
        $sql = "
        CREATE TABLE IF NOT EXISTS persons (
            person_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
            echo "Error creating table persons: " . $connection->error . "<br>";
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

    public function getLastRecord()
    {
        $connection = $this->create_connection();
        $sql = "SELECT * FROM persons ORDER BY person_id DESC LIMIT 1;";
        try {
            $result = $connection->query($sql);
            $row = $result->fetch_assoc();
            $connection->close();
            return [$row['person_id'], $row['address']];
        } catch (mysqli_sql_exception $e) {
            $connection->close();
            echo "$e <br>";
        }
    }

    public function check_val_address()
    {
        $connection = $this->create_connection();
        $last_line = $this->getLastRecord();
        $this->create_address();

        $address = $last_line[1];
        $person_id = $last_line[0];
        $sql = "
        INSERT INTO addresses (address, person_id)
        VALUES ('$address', '$person_id');";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "added a row to addresses<br>";

        } else {
            $connection->close();
            echo "Error inserting in table addresses: " . $connection->error;
        }
    }

    public function check_val_bank()
    {
        $connection = $this->create_connection();
        $last_line = $this->getLastRecord();
        $this->create_bank();

        $bank_account_id = $last_line[1];
        $person_id = $last_line[0];
        $sql = "
        INSERT INTO bank_accounts (bank_account_id, person_id)
        VALUES ('$bank_account_id', '$person_id');";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "added a row to bank_accounts<br>";

        } else {
            $connection->close();
            echo "Error inserting in table addresses: " . $connection->error;
        }
    }

    public function insertTable($data, Environment $twig): Response
    {
        $col_name = "";
        $values = "";
        foreach ($data as $key => $value) {
            // check_val_bank();
            if($value == 'true')
                $values .= "'1'";
            else if($value == 'false')
                $values .= "'0'";
            else
                $values .= "'$value'";
            $col_name .= $key;
            if ($key !== array_key_last($data)) {
                $values .= ", ";
                $col_name .= ", ";
            }
        }
        $connection = $this->create_connection();
        $sql = "
        INSERT INTO persons ($col_name)
        VALUES ($values);";
    
        if ($connection->query($sql) === TRUE) {
            $this->check_val_address();
            $this->check_val_bank();
            $connection->close();
            return new Response($twig->render('success_insert/index.html.twig', [
                'username' => $data['username']
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
            echo "Database '$db_name' created successfully.<br>";
        } else {
            $connection->close();
            echo "Error creating database: " . $connection->error;
        }
    }

}