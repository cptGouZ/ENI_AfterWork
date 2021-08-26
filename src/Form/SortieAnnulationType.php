<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieAnnulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motifannulation', TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label'=>'Motif :',
                'required'=>true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [ 'class'=> 'mx-3 btn btn-outline-success' ],
                'label' => 'Annuler la sortie',
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
