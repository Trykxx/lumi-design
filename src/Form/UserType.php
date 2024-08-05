<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class,[
                'label'=> 'Mot de passe',
                'mapped' => false,
                'required'=>false,
                'empty_data' => ''
            ])
            ->add('lastName')
            ->add('firstName')
            ->add('adress')
            ->add('zipCode')
            ->add('city')
            ->add('phoneNumber')
            // ->add('isActive')
            ->add('isVerified', CheckboxType::class,[
                'disabled'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
