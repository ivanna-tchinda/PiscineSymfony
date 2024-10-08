<?php

namespace App\E02Bundle\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Message;

class DefaultController extends AbstractController
{
    #[Route('/e03', name: 'e03_home')]
    public function index(Environment $twig, Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $filename = $this->getParameter('filename');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $message->getMessage();
            $msg = $data;
            if($message->isTimestamp()){
                $msg .= " " . time() . "\n";
            }
            file_put_contents($filename, $msg, FILE_APPEND);
            return new Response($twig->render('form/index.html.twig', [
                'message_form' => $form->createView(),
                'last_msg' => $msg

            ]));
        }
        return new Response($twig->render('form/index.html.twig', [
            'message_form' => $form->createView(),
            'last_msg' => ""

        ]));
    }
}

?>