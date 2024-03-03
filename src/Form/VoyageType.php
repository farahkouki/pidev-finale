<?php

namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDepart')
            ->add('destination')
            ->add('heureDepart')
            ->add('heureArrivee')
            ->add('typeVoyage', ChoiceType::class, [
                'choices' => [
                    'Traditionnel' => 'Traditionnel',
                    'Volontaire' => 'Volontaire',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'En Solo' => 'En Solo',
                    'En Groupe' => 'En Groupe',
                ],
            ])
           // Autres champs existants...
           ->add('activites', ChoiceType::class, [
            'choices' => [
                'Champs vide' => '',
                'Distribution alimentaire' => 'Distribution alimentaire',
                'Enseignement bénévole' => 'Enseignement bénévole',
                'Soins médicaux' => 'Soins médicaux',
                'Sensibilisation environnement' => 'Sensibilisation environnement',
                'Mentorat jeunes' => 'Mentorat jeunes',
                'Assistance handicapés' => 'Assistance handicapés',
            ],
            'multiple' => true, // Permettre la sélection de plusieurs activités
            'expanded' => true, // Afficher les activités sous forme de cases à cocher
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
