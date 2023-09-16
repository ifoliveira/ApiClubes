<?php

namespace App\Form\Type;

use App\Form\Model\ClubesDto;
use App\Form\Model\EquipoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClubesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nombre', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('direccion', TextType::class)
            ->add('web', TextType::class)
            ->add('email', TextType::class)
            ->add('telefono', TextType::class)
            ->add('codigoAstfut', TextType::class)            
            ->add('equipos', CollectionType::class,  [
                'entry_type' => EquipoFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClubesDto::class,
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