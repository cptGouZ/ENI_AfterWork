<?php

namespace App\Form;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //NOM
            ->add('nom', TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Ville',
            ])

            //CODE POSTAL
            ->add('codePostal', TextType::class, [
                'attr' => [ 'class'=> 'bg-light' ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label' => 'Code Postal',

            ])

            //BOUTON ENREGISTER
            ->add('submit', SubmitType::class, [
                'attr' => [ 'class'=> 'mx-3 btn btn-outline-success' ],
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
            'trim' => true,
            'required' => true,
        ]);
    }
}
