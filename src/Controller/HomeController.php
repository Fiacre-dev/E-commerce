<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
//        $mail=new Mail();
//        $mail->send("azanhounfiacre01@gmail.com","Fiacre AZANHOUN","Mon premier mailjet","Bonjour Fiacre j'espere que tu vas bien ?");
       return $this->render('home/index.html.twig');
    }
}
