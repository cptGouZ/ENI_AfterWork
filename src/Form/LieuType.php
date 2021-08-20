<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom' ,EntityType::class,[
                'label' => 'Lieu : ' ,
                'class' => Lieu::class ,
                'choice_label' => function($lieu) {
                return $lieu->getNom() ;
                }
            ])

        /**
         * TODO créer et submit un nouveau lieu en même temps que la création de sortie
         */
     /*       if (!$options['embedded']) {
                $builder->
                add('lieu', CollectionType::class, [
                    'entry_type' => LieuType::class,
                    'allow_add' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'embedded' => true,
                    ],
                    'label' => false,
                ]);
               $builder->add('submit', SubmitType::class, [
                    'label' => 'Créer',
               ]);
            };*/

            ->add('rue' , TextType::class, [
                'label' => 'Rue :' ,
                'trim' => true ,
                'required' => true ,
            ])

            ->add('ville', EntityType::class,[
                'label' => 'Ville : ' ,
                'class' => Ville::class ,
                'choice_label' => function($ville) {
            return $ville->getNom() ;
              }
            ])

            /*->add('latitude')
            ->add('longitude')*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
