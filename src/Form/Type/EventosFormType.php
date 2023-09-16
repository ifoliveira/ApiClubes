<?php

namespace App\Form\Type;

use App\Form\Model\AsistenciaDto;
use App\Form\Model\EventosDto;
use App\Form\Model\JugadorDto;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('fecha', DateType::class)
            ->add('horaIni', TimeType::class)
            ->add('horaFin', TimeType::class)
            ->add('lugar', TextType::class)
            ->add('tipo', TextType::class)
            ->add('equipo', CollectionType::class,  [
                'entry_type' => EquipoFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                ])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventosDto::class,
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