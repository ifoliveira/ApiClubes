<?php

namespace App\Form\Type;

use App\Form\Model\JugadorDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class JugadorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class)
            ->add('nombre', TextType::class)
            ->add('foto', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JugadorDto::class,
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