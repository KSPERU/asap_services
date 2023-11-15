<?php

namespace App\Form;

use App\Entity\Persona;
use App\Form\UsuarioType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('p_nombre', TextType::class, [
                'constraints' => [
                    new Length(['max' => 64]),
                ],
            ])
            
            ->add('p_apellido', TextType::class, [
                'constraints' => [
                    new Length(['max' => 64]),
                ],
            ])
            
            ->add('p_contacto', TextType::class, [
                'constraints' => [
                    new Length(['max' => 9]),
                    new Regex([
                        'pattern' => '/^\d{9}$/',
                        'message' => 'El número de contacto debe tener exactamente nueve dígitos numéricos.',
                    ]),
                ],
            ])
            
            ->add('p_direccion', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            
            ->add('p_foto', FileType::class, [
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'maxSizeMessage' => 'La imagen no puede ser mayor de {{ limit }} MB.',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Seleccione una imagen válida (JPEG o PNG).',
                    ]),
                ],
            ])
            ->add('usuario', UsuarioType::class, [
                // Opciones para el formulario UsuarioType
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
