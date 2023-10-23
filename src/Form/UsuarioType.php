<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(['message' => 'El correo electrónico no es válido.']),
                    new UniqueEntity(['fields' => 'email', 'message' => 'Este correo electrónico ya está registrado.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
                        'message' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un dígito.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
