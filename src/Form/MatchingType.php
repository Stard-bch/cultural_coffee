<?php

namespace App\Form;

use App\Entity\Matching;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MatchingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
            ])
            ->add('sujetRencontre', ChoiceType::class, [
                'label' => 'Subject',
                'choices' => [
                    'Math' => 'Math',
                    'Science' => 'Science',
                    'History' => 'History',
                    'Art' => 'Art',
                ],
                'placeholder' => 'Choose a subject',
                'required' => true,
            ])
            ->add('nbrPersonneMatchy', IntegerType::class, [
                'label' => 'Number of People',
                'required' => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'Upload Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG)',
                    ])
                ],
            ])
            ->add('numTable', IntegerType::class, [
                'label' => 'Table Number',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matching::class,
        ]);
    }
}
