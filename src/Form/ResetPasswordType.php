<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder ->add('new_password',RepeatedType::class,[
            'type'=> PasswordType::class,
            'invalid_message'=>'Le mot de passe et la confirmation doivent etre identique.',
            'label'=>'Mon nouveau mot de passe',
            'required'=>true,
            'label'=>'Mot de passe',
            'first_options' => [
                'label'=>'Mon nouveau mot de passe',
                'attr'=>[
                    'placeholder'=>'Merci de saisir votre mot de passe'
                ]
            ],
            'second_options' => [
                'label'=>'Mettre à jours mon mot de passe',
                'attr'=>[
                    "class"=>"btn-block btn-info",
                ]
            ]
        ])

            ->add('submit',SubmitType::class,[
                'label'=>"Mettre à jour"
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
