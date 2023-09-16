<?php

namespace App\Form\Type;

use App\Form\Model\EquipoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EquipoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class)
            ->add('nombre', TextType::class)
            ->add('codigoAstfut', TextType::class)
            ->add('categoria', TextType::class)
    /*       ->add('direccion', TextType::class)
            ->add('web', TextType::class)
            ->add('telefono', TextType::class)
            ->add('codigoAstfut', TextType::class)
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EquipoDto::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
         return '';

    }

    public function getName(): string
    {
         return '';

    }

}