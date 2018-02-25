<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileType extends AbstractType{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('name', TextType::class, array('label'=>'label.name', 'required' => true, 'attr' => array('placeholder' => 'placeholder.name', 'class' => 'form-control')));
        $builder->add('lastname', TextType::class, array('label'=>'label.lastname','required' => true, 'attr' => array( 'placeholder' => 'placeholder.lastname', 'class' => 'form-control')));
        $builder->add('phone', TextType::class, array('label'=>'label.phone', 'required' => true, 'attr' => array('placeholder' => 'placeholder.phone','class' => 'form-control')));
        $builder->add('email', EmailType::class, array('label'=>'label.email', 'required' => true, 'attr' => array('placeholder' => 'placeholder.email','class' => 'form-control')));
        $builder->add('username', EmailType::class, array('label'=>'label.username','required' => true, 'constraints' => array(new NotBlank(array('message' => 'username.empty'))), 'attr' => array('class' => 'form-control')));
        $builder->add('usingTime', EntityType::class, array('class' => 'AppBundle:UsingTime', 'choice_label' => 'code', 'choice_translation_domain' => true, 'label'=>'label.usingTime', 'placeholder' => 'placeholder.selectUsingTime','required' => true, 'constraints' => array(new NotBlank(array('message' => 'usingTime.empty'))), 'attr' => array('class' => 'form-control')));
        $builder->add('centre', EntityType::class, array('class' => 'AppBundle:Centre', 'choice_label' => 'name', 'label'=>'label.centre', 'placeholder' => 'placeholder.selectCenter',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'required' => true, 'constraints' => array(new NotBlank(array('message' => 'centre.empty'))), 'attr' => array('class' => 'form-control')));
        $builder->add('department', EntityType::class, array('class' => 'AppBundle:Department', 'choice_label' => 'name', 'label'=>'label.department', 'placeholder' => 'placeholder.selectDepartment',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'required' => false, 'attr' => array('class' => 'form-control')));
        $builder->add('group', EntityType::class, array('class' => 'AppBundle:Group', 'label'=>'label.group', 'placeholder' => 'placeholder.selectGroup',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'choice_label' => function($allChoices, $currentChoiceKey){
                                    return $allChoices->getName().' - '.$allChoices->getLeader()->getName();
                                },'required' => true, 'constraints' => array(new NotBlank(array('message' => 'group.empty'))), 'attr' => array('class' => 'form-control')));
        $builder->add('position', TextType::class, array('label'=>'label.position','required' => true, 'attr' => array('placeholder' => 'placeholder.position', 'class' => 'form-control')));
        $builder->add('softwareSpecifications', TextareaType::class, array('label'=>'label.softwareSpecifications','required' => false, 'attr' => array('placeholder' => 'placeholder.softwareSpecification','class' => 'personalTextarea form-control')));
        $builder->add('description', TextareaType::class, array('label'=>'label.description','required' => false, 'attr' => array('placeholder' => 'placeholder.description', 'class' => 'personalTextarea form-control')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary btn-block formSubmit'), 'label' => 'registerAsClusterUser'));
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) { 
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\User',
                                     'attr' =>['id'=>'userProfile-form', 'novalidate'=>'novalidate'],
                                     'translator' => null,
                                     ));
    }

    public function getBlockPrefix()
    {
        return 'userProfile';
    }
}
