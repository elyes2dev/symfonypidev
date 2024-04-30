<?php

namespace App\Form;

use App\Entity\Form;
use App\Entity\Formquestions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Question;

class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];

        $builder
            ->add('name', TextType::class)
            ->add('creationdate', DateType::class, [
                'widget' => 'single_text',
            ])
            // ->add('formquestions', CollectionType::class, [
            //     'entry_type' => FormQuestionType::class,
            //     'entry_options' => ['questions' => $questions],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Form::class,
            'questions' => [], // Default value for questions
        ]);

        // Add 'questions' option to the allowed options
        $resolver->setRequired(['questions']);
    }
}
