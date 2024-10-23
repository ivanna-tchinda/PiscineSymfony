<?php

namespace App\ex02\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use mysqli_sql_exception;
class DefaultController extends AbstractController
{
    #[Route('/ex02', name: 'home')]
    public function index(Environment $twig): Response
    {
        $this->createDatabase();
        $this->createTable();
        return new Response($twig->render('table/index.html.twig'));
    }

    #[Route('/show_form', name: 'show_form')]
    public function showForm(): Response
    {
        $this->insertTable($_POST);
        $this->getTable();
        return new Response("");
    }

    public function getTable()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex02";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }

        $sql = "SELECT * FROM users";
        if ($result = $connection->query($sql)) {
            $connection->close();
            echo "<table style='border: solid;'>\n";
            echo "<tr style='border: solid;'>\n";
            echo "<td style='border: solid;'>Id</td>\n";
            echo "<td style='border: solid;'>Username</td>\n";
            echo "<td style='border: solid;'>Name</td>\n";
            echo "<td style='border: solid;'>Email</td>\n";
            echo "<td style='border: solid;'>Enable</td>\n";
            echo "<td style='border: solid;'>Birthdate</td>\n";
            echo "<td style='border: solid;'>Address</td>\n";
            echo "</tr>\n";
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='border: solid;'>\n";
                $field0name = $row["id"];
                $field1name = $row["username"];
                $field2name = $row["_name"];
                $field3name = $row["email"];
                $field4name = $row["_enable"] == 1 ? "Yes" : "No";
                $field5name = $row["birthdate"];
                $field6name = $row["_address"];

                echo "<td style='border: solid;'>$field0name</td>\n";
                echo "<td style='border: solid;'>$field1name</td>\n";
                echo "<td style='border: solid;'>$field2name</td>\n";
                echo "<td style='border: solid;'>$field3name</td>\n";
                echo "<td style='border: solid;'>$field4name</td>\n";
                echo "<td style='border: solid;'>$field5name</td>\n";
                echo "<td style='border: solid;'>$field6name</td>\n";
                
                echo "</tr>\n";
            }
            echo "</table>\n";

        } else {
            echo "Error getting infos from table: " . $connection->error;
        }
    }

    public function insertTable($data)
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
        $db_name = "ex02";

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
            } else {
                $connection->close();
                echo "Error inserting in table: " . $connection->error;
            }
    }
    
    public function createDatabase(): Response
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex02";

        // Create connection to MySQL server
        $connection = new \mysqli($db_server, $db_user, $db_pass);

        if ($connection->connect_error) {
            return new Response("Connection failed: " . $connection->connect_error);
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Database '$db_name' created successfully.");
        } else {
            $connection->close();
            return new Response("Error creating database: " . $connection->error);
        }
    }

    public function createTable(): Response
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex02";

        try{
        $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
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
            return new Response("Table 'Users' created successfully.");
        } else {
            $connection->close();
            return new Response("Error creating table: " . $connection->error);
        }
    }
}

?>
