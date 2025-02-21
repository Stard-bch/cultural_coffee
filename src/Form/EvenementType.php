<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_evenement', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description_event', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_event', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prix_event', NumberType::class, [
                'label' => 'Prix de l\'événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('image_event', TextType::class, [
                'label' => 'Image de l\'événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type_event', ChoiceType::class, [
                'label' => 'Type de l\'événement',
                'choices' => [
                    'Conférence' => 'conférence',
                    'Atelier' => 'Atelier',
                    'Concert' => 'Concert',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('capaciteMax', NumberType::class, [
                'label' => 'Capacité maximale',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
    // Custom validation method for date_event
public function validateDateEvent($value, ExecutionContextInterface $context): void
{
    if ($value === null) {
        $context->buildViolation('La date de l\'événement est obligatoire.')
            ->atPath('date_event')
            ->addViolation();
    }
}
}