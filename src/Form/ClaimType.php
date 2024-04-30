<?php

namespace App\Form;

use App\Entity\Claim;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\File;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
class ClaimType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('description', TextType::class, [
            'required' => false,
        ])
       
        ->add('idclub', EntityType::class, [
            'class' => Club::class,
            'choice_label' => 'name',
        ])
        ->add('type', ChoiceType::class, [
            'required' => false,
            'choices' => [
                'Site' => 'site',
                'Store' => 'store',
                'Reservation' => 'reservation',
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->add('image', FileType::class, [
            'required' => false,
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'attr' => ['accept' => 'image/*'], // Autoriser uniquement les fichiers image
        ]);

        

      

// Dans votre formulaire Symfony


        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claim::class,
        ]);
    }
}
