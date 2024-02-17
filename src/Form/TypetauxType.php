<?php

namespace App\Form;

use App\Entity\Typetaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypetauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Add custom HTML classes here
                ],
            ])
            ->add('taux', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Add custom HTML classes here
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typetaux::class,
        ]);
    }
}
