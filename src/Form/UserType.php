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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //PSEUDO
        $builder
            ->add('pseudo' , TextType::class, [
                'label'=> 'Pseudo : ',
                'trim' => true ,
                'required' => true,
            ]);


        //PRENOM
            $builder
                ->add('prenom' , TextType::class, [
                    'label'=> 'Prénom : ',
                    'trim' => true ,
                    'required' => true,
                ]);

        //NOM
            $builder
                ->add('nom' , TextType::class, [
                    'label'=> 'Nom : ',
                    'trim' => true ,
                    'required' => true,
                ]) ;

        //TELEPHONE
            $builder
                ->add('telephone' , TextType::class, [
                    'label'=> 'Téléphone : ',
                    'trim' => true ,
                    'required' => true,
                ]);

        //EMAIL
            $builder
                ->add('email' , EmailType::class ,[
                    'label' => 'Email',
                    'trim' => true ,
                    'required' => true,
                ]);

        //PASSWORD
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                    'required' => true ,
                    'first_options' =>['label' => 'Mot de passe : '],
                    'second_options' =>['label' => 'Confirmation Mot de passe : '],

                ]);

        //CAMPUS
/*            $builder
                ->add('campus' , EntityType::class, [
                    'label'=> 'Campus : ',
                    'required' => true,
                    'class' => Campus::class,
                    'query_builder' => function (CampusRepository $cr) {
                    return $cr->createQueryBuilder('campus')->orderBy('campus.nom' , 'ASC');
                    }
                ]) ;*/

        //BOUTON ENREGISTER
             $builder->add('submit', SubmitType::class, [
                 'label' => 'Enregistrer',
                ]);

        //BOUTON ANNULER
             $builder->add('annuler', ButtonType::class, [
                 'label' => 'Annuler',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
