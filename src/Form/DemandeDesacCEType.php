<?php

namespace App\Form;

use App\Entity\DemandeDesacCE;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DemandeDesacCEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('raison')
            // Ajoutez le champ rib ici
            ->add('compteep', EntityType::class, [
                'class' => 'App\Entity\Compteep', // Assurez-vous de définir la bonne classe ici
                'choice_label' => 'id', // Assurez-vous de définir le label de choix correctement
                'label' => 'Compteep',
                'disabled' => 'true',  // Définissez le label selon vos besoins
                'required' => true, // Définissez la nécessité du champ selon vos besoins
                // Ajoutez d'autres options si nécessaire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeDesacCE::class,
        ]);
    }
}
