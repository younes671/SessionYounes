<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Session;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgrammeType extends AbstractType
{
    // formulaire d'ajout programme 
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('session', EntityType::class, [
                'class' => Session::class,
                'choice_label' => 'titre', 
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'nomSection',  
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
