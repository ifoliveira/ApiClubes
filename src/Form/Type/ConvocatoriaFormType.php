<?php

namespace App\Form\Type;

use App\Form\Model\ConvocatoriaDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ConvocatoriaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('asistencia',TextType::class)
            ->add('justificado', ChoiceType::class, [
                'choices'  => [
                    0 => null,
                    1 => true,
                    2 => false,
                ],
            ])
            ->add('titular', ChoiceType::class, [
                'choices'  => [
                    0 => null,
                    1 => true,
                    2 => false,
                ],
            ])
            ->add('minutos', TextType::class)
            ->add('jugador', CollectionType::class,  [
                'entry_type' => JugadorFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                ])            
            ->add('evento', TextType::class)
            ->add('comentario', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConvocatoriaDto::class,
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