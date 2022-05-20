<?php

namespace App\Form;

use App\Entity\Jugador;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class JugadorNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Introduzca su Nombre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'El nombre no puede ser inferior a {{ limit }} carácteres',
                        'max' => 40,
                        'maxMessage' => 'El nombre no pude ser superior a {{ limit }} carácteres',
                    ])
                ],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Introduzca su Apellido',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'El apellido no puede ser inferior a {{ limit }} carácteres',
                        'max' => 40,
                        'maxMessage' => 'El apellido no pude ser superior a {{ limit }} carácteres',
                    ])
                ],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Introduzca su Teléfono',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'max' => 15,
                        'maxMessage' => 'El télefono no puede ser superior a {{ limit }} caracteres',
                    ])
                ],
                'required' => false,
            ])
            ->add('anoNacimiento',TextType::class, [
                'label' => 'Introduzca su Año de Nacimiento',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => 'El Altura no puede ser inferior a {{ limit }} carácteres',
                        'max' => 5,
                        'maxMessage' => 'El Altura no pude ser superior a {{ limit }} carácteres',
                    ])
                ],
            ])
            ->add('altura', TextType::class, [
                'label' => 'Introduzca su Altura',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'El Altura no puede ser inferior a {{ limit }} carácteres',
                        'max' => 4,
                        'maxMessage' => 'El Altura no pude ser superior a {{ limit }} carácteres',
                    ])
                ],
            ])
            ->add('peso', TextType::class, [
                'label' => 'Introduzca su Peso en Kilogramos',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'El Peso no puede ser inferior a {{ limit }} caracteres',
                        'max' => 4,
                        'maxMessage' => 'El Peso no puede ser superior a {{ limit }} caracteres',
                    ])
                ],
            ])
            ->add('capitan', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Si' => true
                ],
            ])
            ->add('posicion', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 1,
                    '3' => 3,
                    'Segunda' => 4,
                    'Flanker' => 6,
                    '8' => 8,
                    'Medio Melee' => 9,
                    'Apertura' => 10,
                    'Ala Cerrado' => 11,
                    'Primer Centro' => 12,
                    'Segundo Centro' => 13,
                    'Ala Abierto' => 14,
                    'Arrier' => 15
                ],
            ])
            ->add('esChutador', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Si' => true
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jugador::class,
        ]);
    }
}
