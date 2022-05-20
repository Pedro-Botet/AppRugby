<?php

namespace App\Form;

use App\Entity\Staff;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class StaffNewType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
