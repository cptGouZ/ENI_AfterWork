<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Lieu : ',
            ])

            //RUE
            ->add('rue', TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Rue : ',
            ])

            //VILLE
            ->add('ville', EntityType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Ville : ',
                'class' => Ville::class,
                'choice_label' => function ($ville) {
                    return $ville->getNom();
                },
            ])

            //BOUTON ENREGISTER
            ->add('submit', SubmitType::class, [
                'attr' => [ 'class'=> 'btn btn-outline-success mx-3' ],
                'label' => 'Enregistrer',
            ]);


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


            /*->add('latitude')
            ->add('longitude')*/

}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
