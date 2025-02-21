<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description_post', TextareaType::class, [
            'attr' => ['class' => 'form-control', 'placeholder' => 'Avez-vous des pensées?'],
        ])
        ->add('image', FileType::class, [
            'attr' => ['class' => 'form-control'],
            'required' => false,
            'data_class' => null, 
        ]);
            }   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
         ]);
    }
}
