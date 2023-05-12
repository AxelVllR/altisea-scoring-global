<?php

namespace App\Form;

use App\Entity\Wod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom du Wod"
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description"
            ])
            ->add('nb_of_lines', NumberType::class, [
                "label" => "Nombre de ligne",
                
            ])
            
            ->add('nb_of_participants', NumberType::class, [
                "label" => "Nombre de participants"
            ])
            
            ->add('duration', NumberType::class, [
                "label" => "Durée Max du Wod (en min)"
            ])
            ->add('repetions_max', NumberType::class, [
                "label" => "Max Rep",
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wod::class,
        ]);
    }
}
