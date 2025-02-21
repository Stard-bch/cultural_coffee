<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use App\Entity\Evenement;
use App\Entity\User;
use App\Entity\Reservation; 

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_booking', DateType::class, [
                'label' => 'Date de réservation',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('nbr_places', NumberType::class, [
                'label' => 'Nombre de places',
                'required' => true,
            ])
            ->add('statut_booking', ChoiceType::class, [
                'label' => 'Statut de la réservation',
                'choices' => [
                    'Confirmée' => 'confirmée',
                    'En attente' => 'en_attente',
                    'Annulée' => 'annulée',
                ],
            ])
            ->add('moyenPayement_booking', ChoiceType::class, [
                'label' => 'Moyen de paiement',
                'choices' => [
                    'Carte de crédit' => 'carte_credit',
                    'Espèces' => 'especes',
                    'Virement bancaire' => 'virement_bancaire',
                ],
           
            ])
            ->add('evenement', EntityType::class, [
                'label' => 'Événement',
                'class' => Evenement::class,
                'choice_label' => 'titreEvenement', // Use the event title instead of ID
             
            ])
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur',
                'class' => User::class,
                'choice_label' => 'email_user', // Use the user email instead of ID
          
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class, // Ensure this matches your Reservation entity class
        ]);
    }
}