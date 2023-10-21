<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('p_nombre')
            ->add('p_apellido')
            ->add('p_contacto')
            ->add('p_direccion')
            ->add('p_foto')
            ->add('p_cv')
            ->add('p_antpen')
            ->add('p_cert')
            ->add('p_biografia')
            ->add('p_experiencia')
            ->add('p_distrito')
            ->add('p_habilidades')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
