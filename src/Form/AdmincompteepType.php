<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Compteep;
use App\Entity\Typetaux;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmincompteepType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
        $types = $this->entityManager->getRepository(Typetaux::class)->findBy([], ['type' => 'ASC']);
        $typeChoices = [];

        foreach ($types as $type) {
            $typeChoices[$type->getType()] = $type->getType();
        }

        // Replace ChoiceType for 'client' with EntityType to handle entity relationship
        $builder->add('client', EntityType::class, [
            'class' => Client::class,
            'disabled' => $options['is_edit'],
            'choice_label' => 'id', // Or any other property you wish to display
            'placeholder' => 'Choose a client',
            'attr' => [
                'class' => 'default-select form-control wide',
            ],
        ])

        // Modify the 'type' field as before
        ->add('type', ChoiceType::class, [
            'choices' => $typeChoices,
            'placeholder' => 'Choose a type',
            'disabled' => false,
            'attr' => [
                'class' => 'default-select form-control wide',
            ],
        ])

        // Add 'description' field as before
        ->add('description', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Description de lobjectif de ce compte',
            ],
        ])

        // Add 'rib' field as before, but it's disabled and managed automatically
        ->add('rib', null, [
            'disabled' => true,
            'attr' => [
                'class' => 'form-control mb-3',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compteep::class,
            'is_edit' => false,
        ]);
    }
}