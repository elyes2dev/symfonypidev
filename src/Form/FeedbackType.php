<?php

namespace App\Form;

use App\Entity\Feedback;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname(); // Customize this as needed
                },
            ])
            ->add('idReservation')
            ->add('responseQuestion1', TextareaType::class, [
                'label' => 'What did you like about our service?'
            ])
            ->add('responseQuestion2', TextareaType::class, [
                'label' => 'What can we improve?'
            ])
            ->add('responseQuestion3', TextareaType::class, [
                'label' => 'Any other comments?'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
