<?php

namespace App\Form;

use App\Entity\Matching;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Matching Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('sujetRencontre', TextType::class)
            ->add('numTable', IntegerType::class)
            ->add('nbrPersonneMatchy', IntegerType::class)
            ->add('image', FileType::class)
            ->add('assessors', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom_user',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matching::class,
        ]);
    }
}
