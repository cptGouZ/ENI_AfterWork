<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                'class' => Campus::class,
                'choice_label' => function ($campus) {
                    return $campus->getNom();
                }
            ])
            ->add('nom_sortie', TextType::class, [
                'label' => 'Nom de la sortie :',
            ])
            ->add('date_debut', DateTimeType::class, [
                'label' => 'Entre :'
            ])
            ->add('date_fin', DateTimeType::class, [
                'label' => 'et'
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties que j\'organise'
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je participe'
            ])
            ->add('non_inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne participe pas encore'
            ])
            ->add('sortie_passe', CheckboxType::class, [
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
