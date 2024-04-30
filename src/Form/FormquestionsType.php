<?php

namespace App\Form;

use App\Entity\Formquestions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Question;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];

        $builder
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choices' => $questions,
                'choice_label' => 'text',
                'multiple' => true, // Allow multiple selection
                'expanded' => true, // Display as checkboxes
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formquestions::class,
            'questions' => [], // Define a default empty array for questions
        ]);
    }
}
