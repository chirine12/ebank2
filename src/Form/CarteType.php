<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use App\Entity\Carte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le numéro', // Ajoutez le placeholder si nécessaire
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^\d{16}$/',
                        'message' => 'Le numéro de carte doit contenir exactement 16 chiffres.',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le Nom', // Ajoutez le placeholder si nécessaire
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('dateexp')
            ->add('cvv', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le CVV', // Ajoutez le placeholder si nécessaire
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'Le CVV doit contenir exactement 3 chiffres.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}