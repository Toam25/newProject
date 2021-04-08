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
use MeteoConcept\HCaptchaBundle\Form\HCaptchaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Adresse mail'
                ]
            ])
            ->add('password', PasswordType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>"Mot de passe"
                ]
            ])
            ->add('name',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>"Nom"
                ]
            ])
            ->add('firstname',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>"Prenom"
                ]
            ])
            // ->add('birthday',DateType::class,[
            //     'years'=>$this->mydate(),
            //     'format'=>'dd-MMM-yyyy',
            //     'attr'=>[
            //             'class'=>'form-control'
            //         ]
                
            //     ])
            ->add('genre', ChoiceType::class,[
                'choices'=>[
                    'Homme'=>'homme',
                    'Femme'=>'femme'
                    
                ],
                    'attr'=>[
                        'class'=>'form-control'
                    ]
                
            ]
            )
           // ->add('captcha', HCaptchaType::class, [
               // 'label' => 'Anti-bot test',
                // optionally: use a different site key than the default one:
                //'hcaptcha_site_key' => 'd5cadd80-035b-44e3-ae5c-15a844f83877',
            //])
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
