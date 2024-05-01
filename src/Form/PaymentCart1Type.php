<?php

namespace App\Form;

use App\Entity\PaymentCart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentCart1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('HolderName')
            ->add('CardNumber')
            ->add('ExpirationDate')
            ->add('CVV')
            //->add('idCart')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentCart::class,
        ]);
    }
}
