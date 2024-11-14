<?php

namespace App\ex10\Controller;

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
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;


class DefaultController extends AbstractController
{
    #[Route('/ex10', name: 'home')]
    public function index(Request $request, Environment $twig, EntityManagerInterface $entity)
    {
        $filesystem = new Filesystem();

        $this->create_database();
        $this->create_table();
        
        $list_values = $this->get_values_file();
        var_dump($list_values);
        return new Response("ok");
    }

    public function get_values_file()
    {
        $content = file_get_contents('/home/itchinda/Documents/PiscineSymfony/Day05/ex10/d05/file.txt');
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
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex10";

        // Create connection to MySQL server
        $connection = new \mysqli($db_server, $db_user, $db_pass);

        if ($connection->connect_error) {
            return new Response("Connection failed: $connection->connect_error" );
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name;";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Database '$db_name' and table sql_informations created successfully.<br>");
        } else {
            $connection->close();
            return new Response("Error creating database: $connection->error");
        }
    }

    public function create_table()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex10";

        // Create connection to MySQL server
        $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);

        if ($connection->connect_error) {
            return new Response("Connection failed: $connection->connect_error" );
        }

        // Create database if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS sql_informations(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255),
            name VARCHAR(255),
            age INT,
            email VARCHAR(255)
        );";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Table sql_informations created successfully.<br>");
        } else {
            $connection->close();
            return new Response("Error creating database: $connection->error");
        }
    }
}
