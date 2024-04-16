<?php
namespace App\Form;

use App\Entity\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback ;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class EventType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'required'=>false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois fournir un nom.']),
                new Assert\Length([
                    'max' => 16,
                    'maxMessage' => 'Le nom doit avoir au maximum {{ limit }} caractères.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[A-Z].*$/',
                    'message' => 'Le nom doit commencer par une majuscule.',
                ]),
            ],
        ])
        ->add('datedeb', DateType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois sélectionner une date de début.']),
                new Assert\GreaterThanOrEqual([
                    'value' => new \DateTime(),
                    'message' => 'La date de début doit être égale ou postérieure à la date actuelle.',
                ]),
            ],
        ])
        ->add('datefin', DateType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois sélectionner une date de fin.']),
                new Callback([$this, 'validateDateFin']),
            ],
        ])
        
        ->add('starttime', TimeType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois sélectionner une heure de début.']),
                new Callback([$this, 'validateStartTime']),
            ],
        ])
        ->add('endtime', TimeType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois sélectionner une heure de fin.']),
                new Assert\GreaterThan([
                    'propertyPath' => '[starttime]',
                    'message' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
                ]),
                new Callback([$this, 'validateEndTime']),
            ],
        ])
        ->add('nbrParticipants', IntegerType::class, [
            'required'=>false,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Tu dois fournir un nombre de participants.']),
                new Assert\Type([
                    'type' => 'integer',
                    'message' => 'Le nombre de participants doit être un entier.',
                ]),
            ],
        ])
  // Inside the buildForm method of EventType class
->add('price', NumberType::class, [
    'required'=>false,
    'constraints' => [
        new Assert\NotBlank(['message' => 'Tu dois fournir un prix.']),
        new Assert\Type([
            'type' => 'float',
            'message' => 'Le prix doit être un nombre.',
        ]),
        
    ],
])



        ->add('images', FileType::class, [
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'label' => 'Images',
        ]);




}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
    public function validateDateFin($value, ExecutionContextInterface $context): void
    {
        $event = $context->getRoot()->getData();
        
        // Debugging statements
        dump($event->getDatedeb());
        dump($value);
        
        if ($event instanceof Event && $event->getDatedeb() && $value && $event->getDatedeb() > $value) {
            $context->buildViolation('La date de fin doit être égale ou postérieure à la date de début.')
                ->atPath('datefin')
                ->addViolation();
        }
    }
    
    public function validateStartTime($value, ExecutionContextInterface $context): void
    {
        $event = $context->getRoot()->getData();
    
        if ($event instanceof Event && $event->getDatedeb() && $event->getDatefin() && $event->getDatedeb() == $event->getDatefin()) {
            if ($value >= $event->getEndtime()) {
                $context->buildViolation('L\'heure de début doit être antérieure à l\'heure de fin.')
                    ->atPath('starttime')
                    ->addViolation();
            }
        }
    }
    
    public function validateEndTime($value, ExecutionContextInterface $context): void
    {
        $event = $context->getRoot()->getData();
    
        if ($event instanceof Event && $event->getDatedeb() && $event->getDatefin() && $event->getDatedeb() == $event->getDatefin()) {
            if ($value <= $event->getStarttime()) {
                $context->buildViolation('L\'heure de fin doit être postérieure à l\'heure de début.')
                    ->atPath('endtime')
                    ->addViolation();
            }
        }}
    
    }
