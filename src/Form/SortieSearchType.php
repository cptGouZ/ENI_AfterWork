<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Enums\SortieSearchOptions;
use DateTime;
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
            ->add(SortieSearchOptions::CAMPUS, EntityType::class, [
                'label' => 'Campus :',
                'empty_data'=> '',
                'placeholder' => 'Choix du campus',
                'class' => Campus::class,
                'choice_label' => function ($campus) {
                    return $campus->getNom();
                }
            ])
            ->add(SortieSearchOptions::NOM_CONTIENT, TextType::class, [
                'label' => 'Nom de la sortie :',
            ])
            ->add(SortieSearchOptions::DATE_DEBUT, DateType::class, [
                'label' => 'Entre :',
                'widget' => 'single_text',
                'data' => new DateTime('now'),
            ])
            ->add(SortieSearchOptions::DATE_FIN, DateType::class, [
                'label' => 'et le :',
                'widget' => 'single_text',
                'data' => date_add(new DateTime('now'), new \DateInterval('P1M') ),
            ])
            ->add(SortieSearchOptions::MES_SORTIES, CheckboxType::class, [
                'label' => 'Sorties que j\'organise'
            ])
            ->add(SortieSearchOptions::INSCRIT_OUI, CheckboxType::class, [
                'label' => 'Sorties auxquelles je participe'
            ])
            ->add(SortieSearchOptions::INSCRIT_NON, CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne participe pas encore'
            ])
            ->add(SortieSearchOptions::SORTIES_PASSEES, CheckboxType::class, [
                'label' => 'Sorties passées'
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
