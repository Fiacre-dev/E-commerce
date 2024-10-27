<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index($stripeSessionId,Cart $cart): Response
    {
        $order=$this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState()==0){
            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();
            //Envoyer un email à notre client pour lui confirmer sa commande
            $mail = new Mail();
            $content = "Bonjour".$order->getUser()->getFirstname()."<br/>Merci pour votre commande<br/><br/> blablabla";
            $mail->send($order->getUser()->getEmail(),$order->getFirstname(),"Votre commande la boutique .. est bien valide",$content);
            dd($mail);
        }

        //Afficher les quelques informations de la commande de l'utilisateur
        return $this->render('order_success/index.html.twig',[
            'order'=>$order
        ]);
    }
}
