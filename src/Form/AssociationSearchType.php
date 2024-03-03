<?php

// src/Form/AssociationSearchType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AssociationSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'association',
                'required' => false, // Le champ n'est pas obligatoire
                'attr' => ['placeholder' => 'Rechercher par nom']
            ]);
    }
}
