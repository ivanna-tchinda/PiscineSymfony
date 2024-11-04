<?php

namespace App\ex00\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/ex00', name: 'home')]
    public function index(Environment $twig, Request $request)
    {
        return new Response($twig->render('table/index.html.twig'));
    }

    #[Route('/create_table', name: 'create_table')]
    public function createTable()
    {
        $db_server = "localhost";
        $db_user = $this->getParameter('db_user');
        $db_pass = $this->getParameter('db_pass');
        $db_name = "ex00";

        $connection = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

        if(!$connection){
            return new Response("connection failed" . mysqli_connect_error());
        }

        $sql = "
        CREATE TABLE IF NOT EXISTS Users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            _name VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            _enable BOOLEAN,
            birthdate DATETIME,
            _address LONGTEXT
            )";

        try {
            $connection->query($sql);
            return new Response("Table Users created successfully");
        } catch (\Exception $e){
            return new Response("Error creating table: $e");
        }
        $connection->close();
        return new Response("ex00");
    }
}

?>