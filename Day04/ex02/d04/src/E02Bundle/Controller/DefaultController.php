<?php

namespace App\E02Bundle\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageFormType;
use App\Entity\Message;

class DefaultController extends AbstractController
{
    #[Route('/e03', name: 'e03_home')]
    public function index(Environment $twig)
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $filename = $this->getParameter('filename');
        return new Response($twig->render('form/index.html.twig', [
            'message_form' => $form->createView(),
            $data = $message->getMessage(),
            file_put_contents($filename, $data)
        ]));
    }
}

?>