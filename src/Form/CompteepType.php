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

class CompteepType extends AbstractType
{ private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {    $types = $this->entityManager->getRepository(Typetaux::class)->findBy([], ['type' => 'ASC']);

        $typeChoices = [];
        foreach ($types as $type) {
            $typeChoices[$type->getType()] = $type->getType();
        }
        $builder
      
        ->add('type', ChoiceType::class, [
            'choices' => $typeChoices,
            'placeholder' => 'Choose a type',
            'disabled' => $options['is_edit'],
            'attr' => [
                'class' => 'default-select form-control wide', // Ajoutez les classes HTML personnalisées ici
            ],
        ])
            
        ->add('description', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                'placeholder' => 'Description de lobjectif de ce compte', // Ajoutez le placeholder si nécessaire
            ],
        ])
        ->add('rib', null, [
            'disabled' => true,
            'attr' => [
                'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                 // Ajoutez le placeholder si nécessaire
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
