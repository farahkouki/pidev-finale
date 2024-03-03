<?php

// src/Form/EvenementType.php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom d\'événement',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le nom d\'événement.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le nom d\'événement doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom d\'événement ne peut pas dépasser {{ limit }} caractères.',
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
        // Ajoutez d'autres champs si nécessaire
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}

