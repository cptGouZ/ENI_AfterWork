<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class SortieType extends AbstractType
{
    private $security = null;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            //NOM
            ->add('nom', TextType::class, [
                'label'=> 'Nom de la sortie :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true,
            ] )

            //DATE HEURE SORTIE
            ->add('dateheuredebut' , DateTimeType::class,[
                'label'=> 'Date et Heure de la sortie :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

            //DUREE
            ->add('duree', IntegerType::class , [
                'label' => 'Durée (min) : ',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true ,
            ])

            //DATE LIMITE INSCRIPTION
            ->add('datelimiteinscription', DateTimeType::class,[
                'label'=> 'Date Limite d\'inscription :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

            //NOMBRE INSCRIT
            ->add('nbinscriptionmax', IntegerType::class,[
                'label' => 'Nombre de place :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true ,

            //INFOS
            ])
            ->add('infossortie' , TextareaType::class ,[
                'label' => 'Description et infos :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true ,
            ])

            //CAMPUS
            ->add('campus' , TextType::class, [
                'label'=> 'Campus :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'data' => $this->security->getUser()->getCampus()->getNom(),
                'disabled' => true ,
            ])

            //LIEU
            ->add('lieu' ,EntityType::class,[
                'label' => 'Lieu : ' ,
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'class' => Lieu::class ,
                'choice_label' => function($lieu) {
                    return $lieu->getNom() ;
                }
            ])

            //VILLE
            ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'class' => Ville::class,
                'query_builder' => function (VilleRepository $vr) {
                    return $vr->createQueryBuilder('villes')->orderBy('villes.nom', 'ASC');;
                },
                'choice_label' => 'nom' ,
                'mapped' => false ,
            ])

            //BOUTON ENREGISTER
            ->add('submit', SubmitType::class, [
                 'label' => 'Enregistrer',
            ])

            //BOUTON PUBLIER
            ->add('publier', SubmitType::class, [
                 'label' => 'Publier la sortie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
