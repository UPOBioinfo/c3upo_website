<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Centre;
use AppBundle\Form\CentreType;
use AppBundle\Entity\Department;
use AppBundle\Form\DepartmentType;
use AppBundle\Entity\Group;
use AppBundle\Form\GroupType;
use AppBundle\Entity\Leader;
use AppBundle\Form\LeaderType;
use AppBundle\Form\UserVerifyType;
use AppBundle\Form\UserProfileType;

class AdministrationController extends Controller
{
    
    public function indexAction(Request $request)
    {

//        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' =>  $formHTML));
        return $this->render('AppBundle:Administration:index.html.twig');
        
    }
    
    /*************************************************************************
     **************************** USERS **************************************
     *************************************************************************/
    
    /**
     * Action to get users in the system. We can select if we want to see
     * all users, cluster users or pending users
     * the action send them in json format
     */
    public function getUsersJsonAction($clusterStatus, $userStatus){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        
        if($clusterStatus == null){
            $users = $em->getRepository('AppBundle:User')->findAll();
            $tmpFields = true;
        }else if($clusterStatus == -1){
            $users = $em->getRepository('AppBundle:User')->findByIsClusterUser(0);
            $tmpFields = true;
        }else if($clusterStatus == 1){
            if($userStatus == null){
                $users = $em->getRepository('AppBundle:User')->findByIsClusterUser(1);
            }else if($userStatus == 1){
                $users = $em->getRepository('AppBundle:User')->findBy(array('isClusterUser'=>1, 'isActive' => 1));
            }else if($userStatus == -1){
                $users = $em->getRepository('AppBundle:User')->findBy(array('isClusterUser'=>1, 'isActive' => 0));
            }
            $tmpFields = false;
        }
        
        $usersFormat = $em->getRepository('AppBundle:User')->formatUsersJson($users, $tmpFields, $translator);
        
        return new JsonResponse($usersFormat);
    }
    
    /**
     * Action to verify that the registered user has inserted the data correctly and we store
     * the user as "cluster user" and in case he insert new centre / department / group
     * we create them to allow other users use them.
     * 
     * @param type $userId
     * @param Request $request
     * @return JsonResponse
     */
    public function userVerificationAction($userId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $userSelected = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId, 'deleted' => NULL));
        
        $form = $this->createForm(UserVerifyType::class, $userSelected, array('action' => $this->generateUrl("adminstration_user_verification", array('userId' => $userId)), 'translator' => $translator));
            
        $hasError = false;
        $message = '';
        $command = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('userVerification') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $userSelected->setEditor($usr->getId());
                $userSelected->setEdited(new \DateTime());
                $userSelected->setIsClusterUser(1);
                $userSelected->setIsActive(1);
                
                //In case the user is inserting a new centre, department or group we have to create them.
                //"we have to check that doesn't exist a group with that name"
                
                if($form['newCentre']->getData()){
                    //we have to verify that the centre isn't duplicated
                    $centreAux = $em->getRepository('AppBundle:Centre')->findOneByName($userSelected->getCentreTmp());
                    if(!$centreAux){
                        $newCentre = new Centre();
                        $newCentre->setCreator($usr);
                        $newCentre->setCreated(new \DateTime());
                        $newCentre->setName($userSelected->getCentreTmp());
                        $em->persist($newCentre);
                        $centreAux = $newCentre;
                    }
                    $userSelected->setCentre($centreAux);
                }
                
                if($form['newDepartment']->getData()){
                    //we have to verify that the department isn't duplicated
                    $departmentAux = $em->getRepository('AppBundle:Department')->findOneByName($userSelected->getDepartmentTmp());
                    if(!$departmentAux){
                        $newDepartment = new Department();
                        $newDepartment->setCreator($usr);
                        $newDepartment->setCreated(new \DateTime());
                        $newDepartment->setName($userSelected->getDepartmentTmp());
                        $em->persist($newDepartment);
                        $departmentAux = $newDepartment;
                    }
                    $userSelected->setDepartment($departmentAux);
                }
                
                if($form['newGroup']->getData()){
                    //we have to verify that the department isn't duplicated
                    $groupAux = $em->getRepository('AppBundle:Department')->findOneBy(array('name' => $userSelected->getGroupNameTmp()));
                    if(!$groupAux){
                        $newGroup = new Group();
                        $newGroup->setCreator($usr);
                        $newGroup->setCreated(new \DateTime());
                        $newGroup->setName($userSelected->getGroupNameTmp());
                        $newGroup->setCode($userSelected->getGroupCodeTmp());
                        $newGroup->setWebsite($userSelected->getGroupWebsiteTmp());
                        if($form['newLeader']->getData()){
                            $leaderAux = $em->getRepository('AppBundle:Leader')->findOneByName($userSelected->getGroupLeaderTmp());
                            if(!$leaderAux){
                                $newLeader = new Leader();
                                $newLeader->setCreator($usr);
                                $newLeader->setCreated(new \DateTime());
                                $newLeader->setName($userSelected->getGroupLeaderTmp());
                                $em->persist($newLeader);
                                $leaderAux = $newLeader;
                            }
                            $newGroup->setLeader($leaderAux);
                        }else{
                            $newGroup->setLeader($form['leader']->getData());
                        }
                        $em->persist($newGroup);
                        $groupAux = $newGroup;
                    }
                    $userSelected->setGroup($newGroup);
                }
                
                $em->persist($userSelected);
                $em->flush();

                $message = $translator->trans('user.verify.success');
                
                $command = $em->getRepository('AppBundle:user')->getLinuxCommands('verification', $userSelected);
                
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/User/forms:verifyUser.html.twig', array(
            'formUser' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command, 'html_form' => $formHTML));
    }
    
    /**
     * Action to change the status of a user in the cluster
     * 
     * @param type $userId
     * @param type $status
     * @return JsonResponse
     */
    public function userStatusAction($userId, $status){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $userSelected = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId, 'deleted' => NULL));
        $hasError = false;
        $command = '';
        
        if(!$userSelected){
            $hasError = true;
            $message = $translator->trans('error.updating.userStatus');
            
        }else{
            $userSelected->setIsActive($status);
            $userSelected->setEditor($usr->getId());
            $userSelected->setEdited(new \DateTime());
            
            $em->persist($userSelected);
            $em->flush();
            $message = $translator->trans('userStatus.updated.success');
            
            $command = $em->getRepository('AppBundle:user')->getLinuxCommands('status', $userSelected);
        }
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command));
    }
    
    /**
     * Action to change the status of a user in the distribution list
     * 
     * @param type $userId
     * @param type $status
     * @return JsonResponse
     */
    public function distributionListStatusAction($userId, $status){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $userSelected = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId, 'deleted' => NULL));
        $hasError = false;
        
        if(!$userSelected){
            $hasError = true;
            $message = $translator->trans('error.updating.distributionListStatus');
            
        }else{
            $userSelected->setIsInDistributionList($status);
            $userSelected->setEditor($usr->getId());
            $userSelected->setEdited(new \DateTime());
            
            $em->persist($userSelected);
            $em->flush();
            $message = $translator->trans('distributionListStatus.updated.success');
            
            $command = $em->getRepository('AppBundle:user')->getLinuxCommands('distributionList', $userSelected);
        }
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command));
    }
    
    /**
     * Action to edit existing user
     * @param Request $request
     * @return JsonResponse
     */
    public function userEditionAction($userId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $userSelected = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId, 'deleted' => NULL));
        
        $form = $this->createForm(UserProfileType::class, $userSelected, array('action' => $this->generateUrl("adminstration_user_edition", array('userId' => $userId)), 'translator' => $translator));
        
        $hasError = true;
        $message = '';
        $command = '';
        
        $actualUserContent = clone $userSelected;
        
        if ($request->getMethod() == 'POST' && $request->request->has('userProfile') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $userSelected->setEditor($usr->getId());
                $userSelected->setEdited(new \DateTime());
                    
                $em->persist($userSelected);
                $em->flush();

                $message = $translator->trans('user.updated.success');
                $hasError = false;
                
                $command = $em->getRepository('AppBundle:user')->getLinuxCommands('edition', $userSelected, $actualUserContent);
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/User/forms:profile.html.twig', array(
            'formProfile' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command, 'html_form' => $formHTML));
    }
    
    /*************************************************************************
     ************************** CENTERS **************************************
     *************************************************************************/
    
    /**
     * Action to get all centres (not deleted) in the system.
     * the action send them in json format
     */
    public function getCentersJsonAction(){
        $em = $this->getDoctrine()->getManager();
        $centres = $em->getRepository('AppBundle:Centre')->findBy(array('deleted' => NULL));
        $centresFormat = array();
        
        foreach($centres as $c){
            $centresFormat[] = array('id' => $c->getId(), 'name' => $c->getName());
        }
        
        return new JsonResponse($centresFormat);
    }
    
    /**
     * Action to create new centers
     * @param Request $request
     * @return JsonResponse
     */
    public function centreCreationAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre, array('action' => $this->generateUrl("adminstration_centre_creation")));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('centreCreation') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $centre->setCreator($usr);
                $centre->setCreated(new \DateTime());
                    
                $em->persist($centre);
                $em->flush();

                $message = $translator->trans('centre.created.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Centre/forms:create.html.twig', array(
            'formCentre' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    /**
     * Action to edit existing centres
     * @param Request $request
     * @return JsonResponse
     */
    public function centreEditionAction($centreId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $centreSelected = $em->getRepository('AppBundle:Centre')->findOneBy(array('id' => $centreId, 'deleted' => NULL));
        
        $form = $this->get('form.factory')->createNamed('centreEdition', CentreType::class, $centreSelected, array('action' => $this->generateUrl("adminstration_centre_edition", array('centreId' => $centreId))));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('centreEdition') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $centreSelected->setEditor($usr->getId());
                $centreSelected->setEdited(new \DateTime());
                    
                $em->persist($centreSelected);
                $em->flush();

                $message = $translator->trans('centre.updated.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Centre/forms:profile.html.twig', array(
            'profileCentre' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    public function centreDeletionAction($centreId){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
            
        $centreSelected = $em->getRepository('AppBundle:Centre')->findOneBy(array('id' => $centreId, 'deleted' => NULL));

        if($centreSelected){
            /**
             * First we have to check if we have users in the system for that centre.
             */
            $users = $centreSelected->getRegisteredUsers();
            if(count($users) > 0){
                return new JsonResponse(array('error' => true, 'errorMessage' => $translator->trans('error.deleting.centre')));
            }else if(count($users) == 0){

                /**
                 * We delete the centre
                 */
                $centreSelected->setDeleted(new \DateTime());
                $centreSelected->setDeletor($usr->getId());
                
                $em->persist($centreSelected);
                $em->flush();

                return new JsonResponse(array('error' => false, 'message' => $translator->trans('centre.deleted.success')));
            }
        }
        return new JsonResponse(array('error' => true, 'errorMessage' => false));
    }

    /*************************************************************************
     ************************** DEPARTMENTS **********************************
     *************************************************************************/
    
    /**
     * Action to get all departments (not deleted) in the system.
     * the action send them in json format
     */
    public function getDepartmentsJsonAction(){
        $em = $this->getDoctrine()->getManager();
        $departments = $em->getRepository('AppBundle:Department')->findBy(array('deleted' => NULL));
        $departmentsFormat = array();
        
        foreach($departments as $d){
            $departmentsFormat[] = array('id' => $d->getId(), 'name' => $d->getName());
        }
        
        return new JsonResponse($departmentsFormat);
    }
    
    /**
     * Action to create new departments
     * @param Request $request
     * @return JsonResponse
     */
    public function departmentCreationAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department, array('action' => $this->generateUrl("adminstration_department_creation")));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('departmentCreation') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $department->setCreator($usr);
                $department->setCreated(new \DateTime());
                    
                $em->persist($department);
                $em->flush();

                $message = $translator->trans('department.created.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Department/forms:create.html.twig', array(
            'formDepartment' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    /**
     * Action to edit existing department
     * @param Request $request
     * @return JsonResponse
     */
    public function departmentEditionAction($departmentId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $departmentSelected = $em->getRepository('AppBundle:Department')->findOneBy(array('id' => $departmentId, 'deleted' => NULL));
        
        $form = $this->get('form.factory')->createNamed('departmentEdition', DepartmentType::class, $departmentSelected, array('action' => $this->generateUrl("adminstration_department_edition", array('departmentId' => $departmentId))));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('departmentEdition') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $departmentSelected->setEditor($usr->getId());
                $departmentSelected->setEdited(new \DateTime());
                    
                $em->persist($departmentSelected);
                $em->flush();

                $message = $translator->trans('department.updated.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Department/forms:profile.html.twig', array(
            'profileDepartment' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    /**
     * Action to delete an existing department
     * 
     * @param type $departmentId
     * @return JsonResponse
     */
    public function departmentDeletionAction($departmentId){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
            
        $departmentSelected = $em->getRepository('AppBundle:Department')->findOneBy(array('id' => $departmentId, 'deleted' => NULL));

        if($departmentSelected){
            /**
             * First we have to check if we have users in the system for that centre.
             */
            $users = $departmentSelected->getRegisteredUsers();
            if(count($users) > 0){
                return new JsonResponse(array('error' => true, 'errorMessage' => $translator->trans('error.deleting.department')));
            }else if(count($users) == 0){

                /**
                 * We delete the centre
                 */
                $departmentSelected->setDeleted(new \DateTime());
                $departmentSelected->setDeletor($usr->getId());
                
                $em->persist($departmentSelected);
                $em->flush();

                return new JsonResponse(array('error' => false, 'message' => $translator->trans('department.deleted.success')));
            }
        }
        return new JsonResponse(array('error' => true, 'errorMessage' => false));
    }

    
    /*************************************************************************
     ***************************** GROUPS ************************************
     *************************************************************************/
    
    /**
     * Action to get all groups (not deleted) in the system.
     * the action send them in json format
     */
    public function getGroupsJsonAction(){
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('AppBundle:Group')->findBy(array('deleted' => NULL));
        $groupsFormat = array();
        
        foreach($groups as $g){
            $groupsFormat[] = array('id' => $g->getId(), 'name' => $g->getName(), 'code' => $g->getCode(), 'website' => $g->getWebsite(), 'leader' => $g->getLeader()->getName());
        }
        
        return new JsonResponse($groupsFormat);
    }
    
    /**
     * Action to create new groups
     * @param Request $request
     * @return JsonResponse
     */
    public function groupCreationAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group, array('action' => $this->generateUrl("adminstration_group_creation")));
            
        $hasError = false;
        $message = '';
        $command = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('groupCreation') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $group->setCreator($usr);
                $group->setCreated(new \DateTime());
                    
                $em->persist($group);
                $em->flush();

                $message = $translator->trans('group.created.success');
                $command = '$ sudo groupadd '.$group->getCode();
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Group/forms:create.html.twig', array(
            'formGroup' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command, 'html_form' => $formHTML));
    }
    
    /**
     * Action to edit existing groups
     * @param Request $request
     * @return JsonResponse
     */
    public function groupEditionAction($groupId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $groupSelected = $em->getRepository('AppBundle:Group')->findOneBy(array('id' => $groupId, 'deleted' => NULL));
        $oldCode = $groupSelected->getCode();
        
        $form = $this->get('form.factory')->createNamed('groupEdition', GroupType::class, $groupSelected, array('action' => $this->generateUrl("adminstration_group_edition", array('groupId' => $groupId))));
            
        $hasError = false;
        $message = '';
        $command = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('groupEdition') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $groupSelected->setEditor($usr->getId());
                $groupSelected->setEdited(new \DateTime());
                    
                $em->persist($groupSelected);
                $em->flush();

                $message = $translator->trans('group.updated.success');
                $command = '$ groupmod -n '.$groupSelected->getCode().' '.$oldCode;
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Group/forms:profile.html.twig', array(
            'profileGroup' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'command' => $command, 'html_form' => $formHTML));
    }
    
    /**
     * Action to delete an existing group
     * 
     * @param type $groupId
     * @return JsonResponse
     */
    public function groupDeletionAction($groupId){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
            
        $groupSelected = $em->getRepository('AppBundle:Group')->findOneBy(array('id' => $groupId, 'deleted' => NULL));

        if($groupSelected){
            /**
             * First we have to check if we have users in the system for that centre.
             */
            $users = $groupSelected->getRegisteredUsers();
            if(count($users) > 0){
                return new JsonResponse(array('error' => true, 'errorMessage' => $translator->trans('error.deleting.group')));
            }else if(count($users) == 0){

                /**
                 * We delete the centre
                 */
                $groupSelected->setDeleted(new \DateTime());
                $groupSelected->setDeletor($usr->getId());
                
                $em->persist($groupSelected);
                $em->flush();

                $command = '$ sudo groupdel '.$groupSelected->getCode();
                
                return new JsonResponse(array('error' => false, 'message' => $translator->trans('group.deleted.success'), 'command' => $command));
            }
        }
        return new JsonResponse(array('error' => true, 'errorMessage' => false));
    }

    
    /*************************************************************************
     ***************************** LEADERS ***********************************
     *************************************************************************/
    
    /**
     * Action to get all leaders (not deleted) in the system.
     * the action send them in json format
     */
    public function getLeadersJsonAction(){
        $em = $this->getDoctrine()->getManager();
        $leaders = $em->getRepository('AppBundle:Leader')->findBy(array('deleted' => NULL));
        $leadersFormat = array();
        
        foreach($leaders as $l){
            $leadersFormat[] = array('id' => $l->getId(), 'name' => $l->getName());
        }
        
        return new JsonResponse($leadersFormat);
    }
    
    /**
     * Action to create new groups
     * @param Request $request
     * @return JsonResponse
     */
    public function leaderCreationAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $leader = new Leader();
        $form = $this->createForm(LeaderType::class, $leader, array('action' => $this->generateUrl("adminstration_leader_creation")));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('leaderCreation') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $leader->setCreator($usr);
                $leader->setCreated(new \DateTime());
                    
                $em->persist($leader);
                $em->flush();

                $message = $translator->trans('leader.created.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Leader/forms:create.html.twig', array(
            'formLeader' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    /**
     * Action to edit existing leader
     * @param Request $request
     * @return JsonResponse
     */
    public function leaderEditionAction($leaderId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $leaderSelected = $em->getRepository('AppBundle:Leader')->findOneBy(array('id' => $leaderId, 'deleted' => NULL));
        
        $form = $this->get('form.factory')->createNamed('leaderEdition', LeaderType::class, $leaderSelected, array('action' => $this->generateUrl("adminstration_leader_edition", array('leaderId' => $leaderId))));
            
        $hasError = false;
        $message = '';
        
        if ($request->getMethod() == 'POST' && $request->request->has('leaderEdition') ){
            $form->handleRequest($request);
            // Check Form:
            if($form->isValid()){
                
                $leaderSelected->setEditor($usr->getId());
                $leaderSelected->setEdited(new \DateTime());
                    
                $em->persist($leaderSelected);
                $em->flush();

                $message = $translator->trans('leader.updated.success');
            }else{
                $hasError = true;
            }
        }
        $formHTML = $this->renderView('AppBundle:Administration/Leader/forms:profile.html.twig', array(
            'profileLeader' => $form->createView()
            ));
        
        return new JsonResponse(array('error' => $hasError, 'message' => $message, 'html_form' => $formHTML));
    }
    
    /**
     * Action to delete an existing leader
     * 
     * @param type $leaderId
     * @return JsonResponse
     */
    public function leaderDeletionAction($leaderId){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $usr = $this->get('security.token_storage')->getToken()->getUser();
            
        $leaderSelected = $em->getRepository('AppBundle:Leader')->findOneBy(array('id' => $leaderId, 'deleted' => NULL));

        if($leaderSelected){
            /**
             * First we have to check if we have users in the system for that centre.
             */
            $group = $leaderSelected->getGroup();
            if(count($group) > 0){
                return new JsonResponse(array('error' => true, 'errorMessage' => $translator->trans('error.deleting.leader')));
            }else if(count($group) == 0){

                /**
                 * We delete the centre
                 */
                $leaderSelected->setDeleted(new \DateTime());
                $leaderSelected->setDeletor($usr->getId());
                
                $em->persist($leaderSelected);
                $em->flush();

                return new JsonResponse(array('error' => false, 'message' => $translator->trans('leader.deleted.success')));
            }
        }
        return new JsonResponse(array('error' => true, 'errorMessage' => false));
    }
    
    public function getUsersByAction($cmd, $id){
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        
        if($cmd == 'centre'){
            $users = $em->getRepository('AppBundle:User')->findByCentre($id);
        }else if($cmd == 'department'){
            $users = $em->getRepository('AppBundle:User')->findByDepartment($id);
        }else if($cmd == 'group'){
            $users = $em->getRepository('AppBundle:User')->findByGroup($id);
        }
        
        $usersTable = $this->renderView('AppBundle:Administration:usersBy.html.twig', array(
            'users' => $users
            ));
        
        return new JsonResponse(array('error' => true, 'errorMessage' => false, 'users' => $usersTable));
    }
}
