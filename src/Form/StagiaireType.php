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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    // formulaire d'ajout stagiaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'edit'
                ]
                 ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('cp', TextType::class, [
                'attr' => [
                    'class' => 'edit'
                ]])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => ' edit'
                ]])
            // ->add('sessions', EntityType::class, [
            //     'class' => Session::class,
            //     'choice_label' => 'titre',
            //     'multiple' => true,
            // ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'form-control btn btn-secondary'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
