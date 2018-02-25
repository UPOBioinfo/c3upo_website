<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Callback;
use Doctrine\ORM\EntityManagerInterface;

class DepartmentType extends AbstractType{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('name', TextType::class, array('label'=>'label.name','required' => true, 'attr' => array('class' => 'form-control')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary btn-block formSubmit'), 'label' => 'submit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Department',
                                     'attr' =>['novalidate'=>'novalidate'],
                                     'constraints' => array(
                                        new Callback(array($this, 'validateDepartmentInfo'))
                                     )
                                    ));
    }

    public function getBlockPrefix()
    {
        return 'departmentCreation';
    }
    
    /**
     * Function to check if already exist a department with the same name.
     */
    public function validateDepartmentInfo($department, $context){
        $departmentExisting = null;
        
        if($department->getName() != null){
            $departmentExisting = $this->em->getRepository('AppBundle:Department')->findOneBy(array('name' => $department->getName(), 'deleted' => NULL));
        }
        
        if($departmentExisting != null){
            $context->buildViolation('department.name.used')
                    ->atPath('name')
                    ->addViolation();
        }
        
    }
}
