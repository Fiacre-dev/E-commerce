<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){

            $this->addFlash('notice',"Merci de nous avoir contacter.Notre equipe va vous repondre dans les meilleurs delais ");
            $content=$form->getData();
            $mail =new Mail();
            $mail->send('contact@laboutique.bj','La boutique beninoise',"Vous avez recu une nouvelle demande de contact",$content);
        }
        return $this->render('contact/index.html.twig',[
            'form'=>$form
        ]);
    }
}
