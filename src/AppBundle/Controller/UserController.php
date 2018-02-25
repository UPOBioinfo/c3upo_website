<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class UserController extends Controller
{
    
    public function registrationRegisterAction(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        //Initial variables
        $hasError = true;
        $message = '';
        
        // First we have to create a form
        $user = new User();
        $form = $this->createForm(UserType::class, $user, array('action' => $this->generateUrl("registrationRegister"), 'translator' => $translator));
        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);

            if($form->isValid()){
                $hasError = false;
                $user->setRegisterdate(new \Datetime());
//                $user->setSalt(uniqid(mt_rand()));            
                $user->setIsClusterUser(0);
                $user->setIsActive(0);
                $user->setIsInDistributionList(0);
                $em->persist($user);
                $em->flush();
                
                $message = $translator->trans('user.created.success');
                
                //we send an email
                $email = (new \Swift_Message($translator->trans('email.newRegistration.subject')))
                            ->setFrom($this->getParameter('mailer_user'))
                            ->setTo($this->getParameter('mailer_receiver'))
                            ->setBody(
                                $this->renderView(
                                    'AppBundle:Emails:registration.html.twig',
                                    array('user' => $user)
                                ),
                                'text/html'
                            );
                $mailer->send($email);
                
                $form = $this->createForm(UserType::class, new User(), array('action' => $this->generateUrl("registrationRegister"), 'translator' => $translator));
            }
            
//            $formHTML = $this->renderView('AppBundle:Registration:registrationForm.html.twig', array(
//                    'formUser' => $form->createView()
//                ));
        }

//        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' =>  $formHTML));
        return $this->render('AppBundle:Registration:index.html.twig', array('formUser' => $form->createView(), 'message' => $message, 'error' => $hasError));
        
    }
}
