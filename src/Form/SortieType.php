<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom de la sortie :',
                'trim' => true ,
                'required' => true,
            ] )
            ->add('dateHeureDebut' , DateType::class,[
                'label'=> 'Date et Heure de la sortie :',
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('duree', IntegerType::class , [
                'label' => 'Durée (min) : ',
                'trim' => true ,
                'required' => true ,
            ])

            ->add('dateLimiteInscription', DateType::class,[
                'label'=> 'Date Limite d\'inscription :',
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
            ])

            ->add('nbInscriptionMax', IntegerType::class,[
                'label' => 'Nombre de place :',
                'trim' => true ,
                'required' => true ,

            ])
            ->add('infosSortie')
            ->add('campus' , EntityType::class, [
                'label'=> 'Campus :',
                'required' => true,
                'class' => Campus::class,
                'choice_label' => function($campus) {
                    return $campus->getNom() ;
                }
            ])
           // ->add('etat', EntityType::class)
           // ->add('campus')
            //->add('lieu')
           // ->add('inscrits')
           // ->add('organisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
