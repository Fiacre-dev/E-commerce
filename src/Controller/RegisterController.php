<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    private  $entityManager;

    public  function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user=new User();
        $form=$this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user=$form->getData();

            //Hachage de mot  de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }

        return $this->render('register/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
