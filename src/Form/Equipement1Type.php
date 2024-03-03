<?php

namespace App\Form;

use App\Entity\Equipement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class Equipement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_equipe', null, [
            'label' => 'Nom d\'équipe',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le nom d\'équipe.',
                ]),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'Le nom d\'équipe doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom d\'équipe ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('Nbr', null, [
            'label' => 'Nombre',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le nombre.',
                ]),
                new Type([
                    'type' => 'integer',
                    'message' => 'Le nombre doit être un nombre entier.',
                ]),
            ],
        ])
        ->add('type', null, [
            'label' => 'Type',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le type.',
                ]),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'Le type doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le type ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ]);
}
        
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipement::class,
        ]);
    }
}
