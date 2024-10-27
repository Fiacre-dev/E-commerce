<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session',methods: ["POST"])]
    public function index(Request $request,EntityManagerInterface $entityManager, Cart $cart,$reference):JsonResponse
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $order=$entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order){
          new JsonResponse(['error' => 'order' ]);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object=$entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[]=[
                'price_data'=>[
                    'currency'=>'eur',
                    'unit_amount'=>$product->getPrice(),
                    'product_data'=>[
                        'name'=>$product->getProduct(),
                        'images'=>[$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[]=[
            'price_data'=>[
                'currency'=>'eur',
                'unit_amount'=>$order->getCarrierPrice(),
                'product_data'=>[
                    'name'=>$order->getCarrierName(),
                    'images'=>[$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];


        Stripe::setApiKey('sk_test_51PSTY4A8uudCD47DEQVgp18CGo3ATWfCeK3GckYc2xijHqMp1dWYC4rcWCWiIlr1eVnSMRfdqRWvarMrnwIDw0tD004EYhXrev');
        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types'=>['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id ]);
        return $response;
    }
}
