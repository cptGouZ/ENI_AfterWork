<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                'trim' => true ,
                'required' => true,
            ] )

            //DATE HEURE SORTIE
            ->add('dateHeureDebut' , DateTimeType::class,[
                'label'=> 'Date et Heure de la sortie :',
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

            //DUREE
            ->add('duree', IntegerType::class , [
                'label' => 'Durée (min) : ',
                'trim' => true ,
                'required' => true ,
            ])

            //DATE LIMITE INSCRIPTION
            ->add('dateLimiteInscription', DateTimeType::class,[
                'label'=> 'Date Limite d\'inscription :',
                'trim' => true ,
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

            //NOMBRE INSCRIT
            ->add('nbInscriptionMax', IntegerType::class,[
                'label' => 'Nombre de place :',
                'trim' => true ,
                'required' => true ,

            //INFOS
            ])
            ->add('infosSortie' , TextareaType::class ,[
                'label' => 'Description et infos :',
                'trim' => true ,
                'required' => true ,
            ])

            //CAMPUS
            ->add('campus' , TextType::class, [
                'label'=> 'Campus :',
                'trim' => true ,
                'data' => $this->security->getUser()->getCampus()->getNom(),
                'disabled' => true ,
            ])

            //LIEU
            ->add('lieu' ,EntityType::class,[
                'label' => 'Lieu : ' ,
                'class' => Lieu::class ,
                'choice_label' => function($lieu) {
                    return $lieu->getNom() ;
                }
            ])

            //VILLE
            ->add('ville' ,EntityType::class,[
                'label' => 'Ville : ' ,
                'class' => Ville::class ,
                'choice_label' => function($ville) {
                    return $ville->getNom() ;
                }
            ])

            //RUE
            ->add('rue' ,EntityType::class,[
                'label' => 'Rue : ' ,
                'class' => Lieu::class ,
                'choice_label' => function($rue) {
                    return $rue->getRue() ;
                }
            ])

            //CPO
            ->add('cpo' ,EntityType::class,[
                'label' => 'Code Postal : ' ,
                'class' => Ville::class ,
                'choice_label' => function($cpo) {
                    return $cpo->getCodePostal() ;
                }
            ])


            //BOUTON ENREGISTER
            ->add('submit', SubmitType::class, [
                 'label' => 'Enregistrer',
            ])

            //BOUTON PUBLIER
            ->add('publier', SubmitType::class, [
                 'label' => 'Publier la sortie',
            ])

            //BOUTON ANNULER
            ->add('annuler', SubmitType::class, [
                     'label' => 'Annuler',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
