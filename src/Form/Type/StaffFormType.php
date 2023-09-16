<?php

namespace App\Form\Type;

use App\Form\Model\staffDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class StaffFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('foto', TextType::class)
            ->add('tipo', TextType::class)
            ->add('equipo', CollectionType::class,  [
                'entry_type' => EquipoFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                ])
            ->add('club', CollectionType::class,  [
                    'entry_type' => ClubesFormType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => staffDto::class,
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