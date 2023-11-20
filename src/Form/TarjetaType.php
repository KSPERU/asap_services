<?php

namespace App\Form;

use App\Entity\Tarjeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class TarjetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroTarjeta', null, [
                'attr' => [
                    'placeholder' => '#### #### #### ####',
                    'maxlength' => '16',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('fechaVencimiento', null, [
                'attr' => [
                    'placeholder' => 'MM/AA',
                    'maxlength' => '5',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('cvv', null, [
                'attr' => [
                    'placeholder' => '###',
                    'maxlength' => '3',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarjeta::class,
        ]);
    }
}
