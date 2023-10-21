<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('p_nombre', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('p_apellido', TextType::class, [
                'label' => 'Apellido',
            ])
            ->add('p_contacto', TextType::class, [
                'label' => 'Contacto',
            ])
            ->add('p_direccion', TextType::class, [
                'label' => 'Dirección',
            ])
            ->add('p_foto', TextType::class, [
                'label' => 'Foto',
            ])
            ->add('p_cv', TextType::class, [
                'label' => 'CV',
            ])
            ->add('p_antpen', TextType::class, [
                'label' => 'Antecedentes Penales',
            ])
            ->add('p_cert', TextType::class, [
                'label' => 'Certificados',
            ])
            ->add('p_biografia', TextareaType::class, [
                'label' => 'Biografía',
            ])
            ->add('p_experiencia', TextType::class, [
                'label' => 'Experiencia',
            ])
            ->add('p_distrito', TextType::class, [
                'label' => 'Distrito',
            ])
            ->add('p_habilidades', TextareaType::class, [
                'label' => 'Habilidades',
            ])
            ->add('Agregar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
