<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProveedorType extends AbstractType
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
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'La imagen no puede ser mayor de {{ limit }} MB.',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Seleccione una imagen válida (JPEG o PNG).',
                    ]),
                ],
            ])
            
            ->add('p_cv', FileType::class, [
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'El archivo no puede ser mayor de {{ limit }} MB.',
                    ]),
                ],
            ])
            
            ->add('p_antpen', FileType::class, [
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'El archivo no puede ser mayor de {{ limit }} MB.',
                    ]),
                ],
            ])
            
            ->add('p_cert', FileType::class, [
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'El archivo no puede ser mayor de {{ limit }} MB.',
                    ]),
                ],
            ])
            
            ->add('p_biografia', TextareaType::class, [
                'required' => false,
            ])
            
            ->add('p_experiencia', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['max' => 30]),
                ],
            ])
            
            ->add('p_distrito', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['max' => 64]),
                ],
            ])
            
            ->add('p_habilidades', TextareaType::class, [
                'required' => false,
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
