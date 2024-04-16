<?php

namespace App\Form;

use App\Entity\TaskEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class TaskEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', TextType::class, [
            'required' => false,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'max' => 255,
                    'maxMessage' => 'The description cannot be longer than {{ limit }} characters.',
                ]),
            ],
        ])
        ->add('etat', ChoiceType::class, [
            'required' => false,
            'choices' => [
                'Pending' => 'Pending',
                'In Progress' => 'In Progress',
                'Done' => 'Done',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskEvent::class,
        ]);
    }
}
