<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Callback;
use Doctrine\ORM\EntityManagerInterface;

class GroupType extends AbstractType{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('name', TextType::class, array('label'=>'label.name','required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('code', TextType::class, array('label'=>'label.groupCode.short','required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('leader', EntityType::class, array('class' => 'AppBundle:Leader', 'choice_label' => 'name', 'label'=>'label.groupLeader',
                                'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                        ->where('u.deleted IS NULL')
                                        ->orderBy('u.name', 'ASC');
                                },'required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('website', TextType::class, array('label'=>'label.groupWebsite','required' => false, 'attr' => array('class' => 'form-control')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary btn-block formSubmit'), 'label' => 'submit'));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Group',
                                     'attr' =>['novalidate'=>'novalidate'],
                                     'constraints' => array(
                                        new Callback(array($this, 'validateGroupInfo'))
                                     )
                                    ));
    }

    public function getBlockPrefix()
    {
        return 'groupCreation';
    }
    
    
    /**
     * Function to check if already exist a group with the same name.
     */
    public function validateGroupInfo($group, $context){
        $groupExisting = null;
        
        if($group->getName() != null){
            $groupExisting = $this->em->getRepository('AppBundle:Group')->findOneBy(array('name' => $group->getName(), 'deleted' => NULL));
        }
        
        if($groupExisting != null && $groupExisting->getId() != $group->getId()){
            $context->buildViolation('group.name.used')
                    ->atPath('name')
                    ->addViolation();
        }
        
    }
}
