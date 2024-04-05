<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SessionType extends AbstractType
{
    // formulaire d'ajout session
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
            'attr' => [
                'class' => 'edit'
            ]])
            ->add('nbPlace', NumberType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'edit'
                ]
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'edit'
                ]
            ])
            // ->add('inscriptions', EntityType::class, [
            //     'class' => Stagiaire::class,
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            // ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'edit btn btn-primary'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
