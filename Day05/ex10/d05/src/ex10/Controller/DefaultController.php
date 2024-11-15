<?php

namespace App\ex10\Controller;

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


class DefaultController extends AbstractController
{
    #[Route('/ex10', name: 'home')]
    public function index(Environment $twig)
    {
        $this->create_database();
        $this->create_table_sql();

        return new Response($twig->render('home/index.html.twig'));
    }

    #[Route('/add_infos', name: 'add_infos')]
    public function add_infos(Request $request, Environment $twig, EntityManagerInterface $entity)
    {
        $filesystem = new Filesystem();
        
        $list_values = $this->get_values_file();
        // var_dump($list_values);
        $this->add_sql($list_values);
        $this->add_orm($list_values, $entity);

        $repo = $entity->getRepository(Informations::class)->findAll();
        $metadata = $entity->getClassMetadata(Informations::class);
        $columnNames = $metadata->getFieldNames();

        $informations_sql = $this->get_informations_sql();
        $columns_informations_sql = [];

        while ($informations_sql && $col = mysqli_fetch_field($informations_sql))
            array_push($columns_informations_sql, $col->name);

        return new Response($twig->render('tables/index.html.twig', [
            'informations' => $repo,
            'columns' => $columnNames,
            'informations_sql' => $informations_sql,
            'columns_sql' => $columns_informations_sql,
        ]));
    }

    public function get_informations_sql()
    {
        $connection = $this->create_connection();

        $sql = "SELECT * FROM sql_informations";
        try{
            $result = $connection->query($sql);
            $connection->close();
            return $result;

        }  catch (mysqli_sql_exception $e) {
            return 0;
        }
    }

    public function add_sql(Array $list_values)
    {
        $connection = $this->create_connection();

        $sql = "
            INSERT INTO sql_informations (username, name, age, email)
            VALUES ('$list_values[0]','$list_values[1]', '$list_values[2]','$list_values[3]');";
    
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            echo "informations added to table_sql.<br>";
        } else {
            $connection->close();
            echo "Error adding infos to table_sql";
        }
    }

    public function add_orm(Array $list_values, EntityManagerInterface $entity)
    {
        $infos = new Informations();

        $infos->setUsername($list_values[0]);
        $infos->setName($list_values[1]);
        $infos->setAge($list_values[2]);
        $infos->setEmail($list_values[3]);

        $entity->persist($infos); 
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
        $sql = "CREATE DATABASE IF NOT EXISTS ex10;";
        if ($connection->query($sql) === TRUE) {
            $connection->close();
            return new Response("Database 'ex10' and table sql_informations created successfully.<br>");
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
        $db_name = "ex10";

        try{
            $connection = new \mysqli($db_server, $db_user, $db_pass, $db_name);
        } catch (mysqli_sql_exception $e){
            return new Response("Connection failed: Database doesn't exist");
        }
        return $connection;
    }

    public function create_table_sql()
    {
        // Create connection to MySQL server
        $connection = $this->create_connection();

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
