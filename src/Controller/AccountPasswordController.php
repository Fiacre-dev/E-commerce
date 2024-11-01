<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request ,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $manager): Response
    {
        $notification=null;
        $user=$this->getUser();
        $form=$this->createForm(ChangePasswordType::class,$user);

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $old_pwd=$form->get('old_password')->getData();
            if ($passwordHasher->isPasswordValid($user,$old_pwd)){
                $new_pwd=$form->get('new_password')->getData();
                $password= $passwordHasher->hashPassword($user,$new_pwd);

                $user->setPassword($password);

                $manager->flush();
                $notification="Votre mot de passe a été mis à jour";
            }else {
                $notification="Votre mot de passe actuel n'est pas bon";
            }
        }
        return $this->render('account/password.html.twig',
        [
            'form'=>$form->createView(),
            'notification'=> $notification
        ]);
    }
}
