<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'empty_data'=> '',
                'placeholder' => 'Choix du campus',
                'class' => Campus::class,
                'choice_label' => function ($campus) {
                    return $campus->getNom();
                }
            ])
            ->add('nom_sortie', TextType::class, [
                'label' => 'Nom de la sortie :',
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Entre :'
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'et'
            ])
            ->add('mes_sorties', CheckboxType::class, [
                'label' => 'Sorties que j\'organise'
            ])
            ->add('inscrit_oui', CheckboxType::class, [
                'label' => 'Sorties auxquelles je participe'
            ])
            ->add('inscrit_non', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne participe pas encore'
            ])
            ->add('sorties_passees', CheckboxType::class, [
                'label' => 'Sorties passées'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'trim' => true,
            'required' => false,
        ]);
    }
}
