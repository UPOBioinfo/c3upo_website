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

class UserVerifyType extends AbstractType{

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
                                },'required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('centre_tmp', TextType::class, array('label'=>'label.centre', 'required' => true, 'attr' => array('placeholder' => 'placeholder.centreName','class' => 'form-control')));
        $builder->add('department', EntityType::class, array('class' => 'AppBundle:Department', 'choice_label' => 'name', 'label'=>'label.department', 'placeholder' => 'placeholder.selectDepartment',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'required' => false, 'attr' => array('class' => 'form-control')));
        $builder->add('department_tmp', TextType::class, array('label'=>'label.department', 'required' => false, 'attr' => array('placeholder' => 'placeholder.departmentName', 'class' => 'form-control')));
        $builder->add('group', EntityType::class, array('class' => 'AppBundle:Group', 'label'=>'label.group', 'placeholder' => 'placeholder.selectGroup',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'choice_label' => function($allChoices, $currentChoiceKey){
                                    return $allChoices->getName().' - '.$allChoices->getLeader()->getName();
                                },'required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('groupName_tmp', TextType::class, array('label'=>'label.groupName','required' => true, 'attr' => array('placeholder' => 'placeholder.groupName','class' => 'form-control')));
        $builder->add('groupCode_tmp', TextType::class, array('label'=>'label.groupCode','required' => true, 'attr' => array('placeholder' => 'placeholder.groupCode','class' => 'form-control')));
        $builder->add('groupWebsite_tmp', TextType::class, array('label'=>'label.groupWebsite','required' => false, 'attr' => array('placeholder' => 'placeholder.groupWebsite', 'class' => 'form-control')));
        $builder->add('leader', EntityType::class, array('mapped' => false, 'class' => 'AppBundle:Leader', 'choice_label' => 'name', 'label'=>'label.groupLeader', 'placeholder' => 'placeholder.selectLeader',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('groupLeader_tmp', TextType::class, array('label'=>'label.groupLeader','required' => true, 'attr' => array('placeholder' => 'placeholder.groupLeader','class' => 'form-control')));
        $builder->add('position', TextType::class, array('label'=>'label.position','required' => true, 'attr' => array('placeholder' => 'placeholder.position', 'class' => 'form-control')));
        $builder->add('softwareSpecifications', TextareaType::class, array('label'=>'label.softwareSpecifications','required' => false, 'attr' => array('placeholder' => 'placeholder.softwareSpecification','class' => 'personalTextarea form-control')));
        $builder->add('description', TextareaType::class, array('label'=>'label.description','required' => false, 'attr' => array('placeholder' => 'placeholder.description', 'class' => 'personalTextarea form-control')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary btn-block formSubmit'), 'label' => 'registerAsClusterUser'));
        $builder->add('newCentre', HiddenType::class, array('mapped' => false));
        $builder->add('newDepartment', HiddenType::class, array('mapped' => false));
        $builder->add('newGroup', HiddenType::class, array('mapped' => false));
        $builder->add('newLeader', HiddenType::class, array('mapped' => false));
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) {
            //In case the user has some temporal info we have to set the "new_x" variable to 1
            $form = $event->getForm();
            $user = $event->getData();
            
            $form->get('newCentre')->setData(0);
            if($user->getCentreTmp() != null){
                $form->get('newCentre')->setData(1);
            }
            
            $form->get('newDepartment')->setData(0);
            if($user->getDepartmentTmp() != null){
                $form->get('newDepartment')->setData(1);
            }
            
            $form->get('newGroup')->setData(0);
            if($user->getGroupNameTmp() != null){
                $form->get('newGroup')->setData(1);
                //we have to check if the selected leader was a new one or an existing one
                $leader = $this->em->getRepository('AppBundle:Leader')->findOneByName($user->getGroupLeaderTmp());
                $form->get('newLeader')->setData(0);
                if(!$leader){
                    $form->get('newLeader')->setData(1);
                }else{
                    $form->get('leader')->setData($leader);
                }
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $translator = $options['translator'];
            $newUser = $event->getData();
            $form = $event->getForm();

            $newCentre = $form->get('newCentre')->getData();
            $newGroup = $form->get('newGroup')->getData();
            $newLeader = $form->get('newLeader')->getData();
             
            //Depending if the user is selecting a new centre or selecting an existing one we have to do some extra checkings
            if($newCentre){
                //In case the user is creating a new centre we have to check that he insert a centre name
                $centreNameTmp = $form->get('centre_tmp')->getData();
                if(trim($centreNameTmp) == '') {
                    $form->get('centre_tmp')->addError(new FormError($translator->trans('centre.empty')));
                }
            }else{
                //In case the user is selecting a centre we have to check that the selected center exist
                $centre = $form->get('centre')->getData();
                if($centre == null || !is_a($centre, \AppBundle\Entity\Centre::class)){
                    $form->get('centre')->addError(new FormError($translator->trans('centre.empty')));
                }
            }
            
            if($newGroup){
                //In case the user is creating a new group we have to check that he insert a group name
                $groupNameTmp = $form->get('groupName_tmp')->getData();
                if(trim($groupNameTmp) == '') {
                    $form->get('groupName_tmp')->addError(new FormError($translator->trans('groupName.empty')));
                }

                if($newLeader){
                    $groupLeader = $form->get('groupLeader_tmp')->getData();
                    if(trim($groupLeader) == '') {
                        $form->get('groupLeader_tmp')->addError(new FormError($translator->trans('groupLeader.empty')));
                    }
                }else{
                    //We store the real name of the leader in the tmp field.
                    $newUser->setGroupLeaderTmp($form->get('leader')->getData()->getName());
                }
                $groupCodeTmp = $form->get('groupCode_tmp')->getData();
                if(trim($groupCodeTmp) == '') {
                    $form->get('groupCode_tmp')->addError(new FormError($translator->trans('groupCode.empty')));
                }
            }else{
                //In case the user is selecting a group we have to check that the selected center exist
                $group = $form->get('group')->getData();
                if($group == null || !is_a($group, \AppBundle\Entity\Group::class)){
                    $form->get('group')->addError(new FormError($translator->trans('group.empty')));
                }
            }
            
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\User',
                                     'attr' =>['id'=>'userVerification-form', 'novalidate'=>'novalidate'],
                                     'translator' => null,
                                     ));
    }

    public function getBlockPrefix()
    {
        return 'userVerification';
    }
}
