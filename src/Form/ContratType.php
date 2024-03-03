<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Contrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Correctly included for the file upload
use Symfony\Component\Validator\Constraints as Assert; // Correctly included for validation constraints

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedeb', DateTimeType::class, [
                'label' => 'date début',
                'widget' => 'single_text', // Correct key is 'widget' not 'date_widget'
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ])
            ->add('datefin', DateTimeType::class, [ // Specify the type if needed, or use 'null' for default type
                'label' => 'date fin',
                'widget' => 'single_text', // Use 'widget', corrected from the initial code
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ])
            ->add('phoneNumber',null , [
                'label' => 'numero de téléphone',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ])
            ->add('signatureFile', FileType::class, [
                'label' => 'Signature (PNG file)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Assert\File([
                        'mimeTypes' => ['image/png'],
                        'mimeTypesMessage' => 'Please upload a valid PNG image',
                    ])
                ],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ])
            ->add('type', null, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label'=> 'prenom'.'nom',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'font-weight-bold'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
