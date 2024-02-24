<?php

namespace App\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Credit;
use App\Entity\Typecredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CreditType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     $types = $this->entityManager->getRepository(Typecredit::class)->findBy([], ['nom' => 'ASC']);

        $typeChoices = [];
        foreach ($types as $type) {
            $typeChoices[$type->getNom()] = $type->getNom();
        }
        $disabled = isset($options['is_edit']) ? $options['is_edit'] : false;
        $builder
            ->add('type',ChoiceType::class, [
                'choices' => $typeChoices,
                'placeholder' => 'Choose a type',
                'disabled' => $disabled,
                'attr' => [
                    'class' => 'default-select form-control wide', // Ajoutez les classes HTML personnalisées ici
                ],
            ])
            ->add('montant', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le monatnt', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('payement',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'Le payement', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('duree',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'La durée du crédit', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('datedeb',)
            ->add('datefin')
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}
