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

        //PSEUDO
        $builder
            ->add('pseudo' , TextType::class, [
                'label'=> 'Pseudo :',
                'label_attr' => [
                    'class' => 'col-sm-12',
                ],
                'trim' => true ,
                'required' => true,
                'disabled' => $options['is_view'],
            ]);


        //PRENOM
            $builder
                ->add('prenom' , TextType::class, [
                    'label'=> 'Prénom :',
                    'label_attr' => [
                        'class' => 'col-sm-12',
                    ],
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]);

        //NOM
            $builder
                ->add('nom' , TextType::class, [
                    'label'=> 'Nom :',
                    'label_attr' => [
                        'class' => 'col-sm-12',
                    ],
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]) ;

        //TELEPHONE
            $builder
                ->add('telephone' , TextType::class, [
                    'label'=> 'Téléphone :',
                    'label_attr' => [
                        'class' => 'col-sm-12',
                    ],
                    'trim' => true ,
                    'required' => true,
                    'disabled' => $options['is_view'],
                ]);

        //EMAIL
            $builder
                ->add('email' , EmailType::class ,[
                    'label_attr' => [
                        'class' => 'col-sm-12',
                    ],
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
                    'first_options' => ['label' => 'Mot de passe :',
                                        'label_attr' => [
                                            'class' => 'col-sm-12',
                                            ],
                                        ],
                    'second_options' =>['label' => 'Confirmation Mot de passe :',
                                        'label_attr' => [
                                            'class' => 'col-sm-12',
                                            ],
                                        ],
                    'attr' => ['class' => 'bg-light'],
                    'disabled' => $options['is_view'],

                ]);

            //CAMPUS
            $builder
                ->add('campus' , TextType::class, [
                    'label'=> 'Campus :',
                    'label_attr' => [
                        'class' => 'col-sm-12',
                    ],
                    'trim' => true ,
                    'data' => $this->security->getUser()->getCampus()->getNom(),
                    'disabled' => true ,
                ]);

        //BOUTON ENREGISTER
             $builder->add('submit', SubmitType::class, [
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
