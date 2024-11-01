<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prenom',TextType::class,[
                'label'=>"Votre prenom",
                'attr'=>[
                    'placeholder'=>"Merci de saisir votre prenom"
                ]
            ])
            ->add('nom',TextType::class,[
                'label'=>" Votre nom",
                'attr'=>[
                    'placeholder'=>"Merci de saisir votre nom"
                ]
            ])
            ->add('email',EmailType::class,[
                'label'=>" Votre email",
                'attr'=>[
                    'placeholder'=>"Merci de saisir votre adresse email"
                ]
                ])
            ->add('content',TextareaType::class,[
                'label'=>" Votre message",
                'attr'=>[
                    'placeholder'=>"En quoi pouvons-nous vous aidez?"
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>" Envoyer",
                'attr'=>[
                    'class'=>"btn-block btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
