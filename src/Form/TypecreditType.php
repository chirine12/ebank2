<?php

namespace App\Form;

use App\Entity\Typecredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypecreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le type de crédit', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('taux', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le Taux ', // Ajoutez le placeholder si nécessaire
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typecredit::class,
        ]);
    }
}
