<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Virement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VirementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('source')
        ->add('destinataire', TextType::class, [
            'attr' => [
                'inputmode' => 'numeric',
                'pattern'   => '[0-9]*',
                'maxlength' => 11,
                'onkeypress' => "return event.charCode >= 48 && event.charCode <= 57"
            ],
        ])
        ->add('montant', TextType::class, [
            'attr' => [
                'inputmode' => 'numeric',
                'pattern'   => '[0-9]*',
                'maxlength' => 6,
                'onkeypress' => "return event.charCode >= 48 && event.charCode <= 57"
            ],
        ])
        ->add('motif', TextareaType::class) // Ajoutez votre champ 'description' ici
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Virement::class,
        ]);
    }
}
