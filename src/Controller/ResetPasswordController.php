<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        if ($request->get('email')){
            $user=$this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user){
                $resetPassword =new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTime());

                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();


                $url=$this->generateUrl("update_password",[
                    "token"=>$resetPassword->getToken()
                ]);
                $content="Bonjour".$user->getFirstname()."<br>Vous avez demandé à reinitialiser votre mot de passe sur le site la boutique .. .<br><br>";
                $content .="Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'> mettre à jour votre mot de passe</a>";
                $email =new Mail();
                $email->send($user->getEmail(),$user->getFirstname()." ".$user->getLastname(),"Réinstialiser votre mot de passe sur la boutique ...",$content);
                $this->addFlash('notice',"Vous allez recevoir un mail dans quelques seconde avec la procedure pour reinitialiser votre mot de passe");
            }
            else{
                $this->addFlash('notice',"Cette adresse email est inconnu");
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token,UserPasswordHasherInterface $passwordHasher): Response
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$resetPassword){
          return  $this->redirectToRoute('reset_password');
        }
        $now=new \DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('3 hours')){
            $this->addFlash('notice',"Votre demande de mot de passe à ete expiré.Merci de la renouveller");
            return  $this->redirectToRoute('reset_password');
        }

        $form =$this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $new_pwd = $form->get("new_password")->getData();

            $password= $passwordHasher->hashPassword($resetPassword->getUser(),$new_pwd);
            $resetPassword->getUser()->setPassword($password);
            $this->entityManager->flush();
            $this->addFlash('notice',"Votre mot de passe a été mis à jour");
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig',[
            'form'=>$this->createView()
        ]);
        dd($resetPassword);
    }
}
