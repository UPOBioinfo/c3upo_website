<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Callback;
use Doctrine\ORM\EntityManagerInterface;

class CentreType extends AbstractType{
    
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
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Centre',
                                     'attr' =>['novalidate'=>'novalidate'],
                                     'constraints' => array(
                                        new Callback(array($this, 'validateCentreInfo'))
                                     )
                                    ));
    }

    public function getBlockPrefix()
    {
        return 'centreCreation';
    }
    
    /**
     * Function to check if already exist a centre with the same name.
     */
    public function validateCentreInfo($centre, $context){
        $centreExisting = null;
        
        if($centre->getName() != null){
            $centreExisting = $this->em->getRepository('AppBundle:Centre')->findOneBy(array('name' => $centre->getName(), 'deleted' => NULL));
        }
        
        if($centreExisting != null){
            $context->buildViolation('centre.name.used')
                    ->atPath('name')
                    ->addViolation();
        }
        
    }
}
