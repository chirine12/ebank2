<?php

namespace App\Form;

use App\Entity\Assurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'habitation' => 'habitation',
                    'de vie' => 'de vie',
                    'automobile' => 'automobile',
                ],
                'attr' => ['class' => 'form-control wiphp bin/console cache:clear
                de']
            ])
            ->add('delais', null, [
                'label' => 'Delais',
                'attr' => ['class' => 'form-control wide']
            ])
            ->add('montant', null, [
                'label' => 'Montant',
                'attr' => ['class' => 'form-control wide']
            ])
            ->add('client', null, [
                'label' => 'Client',
                'attr' => ['class' => 'form-control wide']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
