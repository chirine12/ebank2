<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du bénéficiaire'],
            'constraints' => [
                new NotBlank(['message' => 'Le nom est obligatoire.']),
            ],
        ])
        ->add('prenom', TextType::class, [
            'attr' => ['class' => 'form-control', 'placeholder' => 'Prénom du bénéficiaire'],
            'constraints' => [
                new NotBlank(['message' => 'Le prénom est obligatoire.']),
            ],
        ])
        ->add('Rib', TextType::class, [
            'label' => 'RIB',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'RIB du bénéficiaire',
                'inputmode' => 'numeric',
                'pattern'   => '[0-9]*',
                'maxlength' => 11,
                'onkeypress' => "return event.charCode >= 48 && event.charCode <= 57"
            ],
            'constraints' => [
                new NotBlank(['message' => 'Le RIB est obligatoire.']),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beneficiaire::class,
        ]);
    }
}