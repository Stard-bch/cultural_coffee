<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_user', ChoiceType::class, [
                'label' => 'Type d\'utilisateur',
                'choices' => [
                    'Admin' => 'admin',
                    'Client' => 'client',
                    'Organisateur' => 'organisateur',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('role_user', ChoiceType::class, [
                'label' => 'Rôle de l\'utilisateur',
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ORGANIZER' => 'ROLE_ORGANIZER',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('nom_user', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('prenom_user', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('email_user', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('telephone_user', IntegerType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('photo_user', TextType::class, [
                'label' => 'Photo',
                'attr' => ['class' => 'form-control'],
                'required' => false, // Assuming the photo is optional
            ])
            ->add('dateNaissance_user', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Ensure this matches your User entity class
        ]);
    }
}