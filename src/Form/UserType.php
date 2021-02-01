<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('password', PasswordType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('name',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('firstname',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('birthday',DateType::class,[
                'years'=>$this->mydate(),
                'format'=>'dd-MMM-yyyy',
                'attr'=>[
                        'class'=>'form-control'
                    ]
                
                ])
            ->add('genre', ChoiceType::class,[
                'choices'=>[
                    'Homme'=>'homme',
                    'Femme'=>'femme',
                    'Autre'=>'autre'
                ],
                    'attr'=>[
                        'class'=>'form-control'
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
