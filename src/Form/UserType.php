<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('name')
            ->add('firstname')
            ->add('birthday',DateType::class,[
                'years'=>$this->mydate(),
                'format'=>'dd-MMM-yyyy',
                ])
            ->add('genre', ChoiceType::class,[
                'choices'=>[
                    'Homme'=>'homme',
                    'Femme'=>'femme',
                    'Autre'=>'autre'
                ]
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    function mydate(){
        $mydate=[];
        $plage= intval(date('Y'))-1960;
        
        for($i=$plage;$i>0;$i--){
            $date=1960+intval($i);
            array_push($mydate,$date);
        }
      return $mydate;
    }
}
