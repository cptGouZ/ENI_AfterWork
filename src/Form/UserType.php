<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{

    private $security = null;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $colorField = 'bg-light';
        if($options['is_view']){
            $colorField = 'bg-secondary text-white';
        }
        //PSEUDO
        $builder
            ->add('pseudo' , TextType::class, [
                'attr' => [ 'class'=> $colorField ],
                'label_attr' => [ 'class' => 'col-sm-12' ],
                'label'=> 'Pseudo :',
                'trim' => true ,
                'required' => true,
                'disabled' => $options['is_view'],
            ]);


        //PRENOM
            $builder
                ->add('prenom' , TextType::class, [
                    'attr' => [ 'class'=> $colorField ],
                    'label_attr' => [ 'class' => 'col-sm-12' ],
                    'label'=> 'Prénom :',
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]);

        //NOM
            $builder
                ->add('nom' , TextType::class, [
                    'attr' => [ 'class'=> $colorField ],
                    'label_attr' => [ 'class' => 'col-sm-12' ],
                    'label'=> 'Nom :',
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]) ;

        //TELEPHONE
            $builder
                ->add('telephone' , TextType::class, [
                    'attr' => [ 'class'=> $colorField ],
                    'label_attr' => [ 'class' => 'col-sm-12' ],
                    'label'=> 'Téléphone :',
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]);

        //EMAIL
            $builder
                ->add('email' , EmailType::class ,[
                    'attr' => [ 'class'=> $colorField ],
                    'label_attr' => [ 'class' => 'col-sm-12' ],
                    'label' => 'Email :',
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]);

        //PASSWORD
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                    'required' => true ,
                    'first_options' => [
                        'attr' => [ 'class'=> $colorField ],
                        'label_attr' => [ 'class' => 'col-sm-12' ],
                        'label' => 'Mot de passe :',
                        ],
                    'second_options' =>[
                        'attr' => [ 'class'=> $colorField ],
                        'label_attr' => [ 'class' => 'col-sm-12 mt-3' ],
                        'label' => 'Confirmation Mot de passe :',
                        ],
                    'disabled' => $options['is_view'],

                ]);

            //CAMPUS
            $builder
                ->add('campus' , TextType::class, [
                    'attr' => [ 'class'=> 'bg-secondary text-white' ],
                    'label_attr' => [ 'class' => 'col-sm-12' ],
                    'label'=> 'Campus :',
                    'trim' => true ,
                    'data' => $this->security->getUser()->getCampus()->getNom(),
                    'disabled' => true ,
                ]);

        //BOUTON ENREGISTER
             $builder->add('submit', SubmitType::class, [
                 'attr' => ['class'=>'btn btn-outline-success'],
                 'label' => 'Enregistrer',
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_view' => false,
        ]);
    }
}
