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
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Campus :',
                'empty_data'=> '',
                'placeholder' => 'Choix du campus',
                'class' => Campus::class,
                'choice_label' => function ($campus) {
                    return $campus->getNom();
                }
            ])
            ->add(SortieSearchOptions::NOM_CONTIENT, TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Nom de la sortie :',
            ])
            ->add(SortieSearchOptions::DATE_DEBUT, DateType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Entre :',
                'widget' => 'single_text',
                'data' => new DateTime('now'),
            ])
            ->add(SortieSearchOptions::DATE_FIN, DateType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'et le :',
                'widget' => 'single_text',
                'data' => date_add(new DateTime('now'), new \DateInterval('P1M') ),
            ])
            ->add(SortieSearchOptions::MES_SORTIES, CheckboxType::class, [
                'attr' => [ 'class'=> 'bg-light mt-3' ],
                'label_attr' => [ 'class' => 'col-sm-12 mt-3' ],
                'label' => 'Sorties que j\'organise'
            ])
            ->add(SortieSearchOptions::INSCRIT_OUI, CheckboxType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Sorties auxquelles je participe'
            ])
            ->add(SortieSearchOptions::INSCRIT_NON, CheckboxType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Sorties auxquelles je ne participe pas encore'
            ])
            ->add(SortieSearchOptions::SORTIES_PASSEES, CheckboxType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Sorties pass??es'
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
