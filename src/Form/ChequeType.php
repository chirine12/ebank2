<?php

namespace App\Form;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use App\Entity\Cheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChequeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'numero ', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('numcompte', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'numero de compte', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('montant', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'montant', // Ajoutez le placeholder si nécessaire
                ],
            ])
            ->add('signature', FileType::class, [
                'mapped' => false,
            ])
            ->add('date')
            
            ->add('mail', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3', // Ajoutez les classes HTML personnalisées ici
                    'placeholder' => 'mail', // Ajoutez le placeholder si nécessaire
                ],
            ])
        
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cheque::class,
        ]);
    }
}
