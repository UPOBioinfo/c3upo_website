<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Callback;
use Doctrine\ORM\EntityManagerInterface;

class LeaderType extends AbstractType{

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
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Leader',
                                     'attr' =>['novalidate'=>'novalidate'],
                                     'constraints' => array(
                                        new Callback(array($this, 'validateLeaderInfo'))
                                     )
                                    ));
    }

    public function getBlockPrefix()
    {
        return 'leaderCreation';
    }
    
    /**
     * Function to check if already exist a leader with the same name.
     */
    public function validateLeaderInfo($leader, $context){
        $laderExisting = null;
        
        if($leader->getName() != null){
            $laderExisting = $this->em->getRepository('AppBundle:Leader')->findOneBy(array('name' => $leader->getName(), 'deleted' => NULL));
        }
        
        if($laderExisting != null){
            $context->buildViolation('leader.name.used')
                    ->atPath('name')
                    ->addViolation();
        }
        
    }
}
