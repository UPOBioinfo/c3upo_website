<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\UsingTime;

/**
 * Description of UserRepository
 *
 * @author Sofia Moreno Oñate <sofmorona@gmail.com>
 */
class UserRepository extends EntityRepository{
    
    public function formatUsersJson($users, $tmpFields, $translator){
        $usersFormat = array();
        
        $counter = 0;
        foreach($users as $u){
            
            $usersFormat[$counter]['id'] = $u->getId();
            $usersFormat[$counter]['isClusterUser'] = $translator->trans('isClusterUser_'.(int)$u->getIsClusterUser());
            $usersFormat[$counter]['isClusterUser_code'] = $u->getIsClusterUser();
            $usersFormat[$counter]['isActive'] = $translator->trans('isUserActive_'.(int)$u->getIsActive());
            $usersFormat[$counter]['isActiveUser_code'] = $u->getIsActive();
            $usersFormat[$counter]['isInListDistribution'] = $translator->trans('isInDistributionList_'.(int)$u->getIsInDistributionList());
            $usersFormat[$counter]['isInListDistribution_code'] = $u->getIsInDistributionList();
            $usersFormat[$counter]['name'] = $u->getName();
            $usersFormat[$counter]['lastname'] = $u->getLastname();
            $usersFormat[$counter]['position'] = $u->getPosition();
            $usersFormat[$counter]['phone'] = $u->getPhone();
            $usersFormat[$counter]['email'] = $u->getEmail();
            $usersFormat[$counter]['username'] = $u->getUsername();
            if($u->getUsingTime()){
                $usersFormat[$counter]['usingTime'] = $translator->trans($u->getUsingTime()->getCode());
            }
            if($u->getCentre()){
                $usersFormat[$counter]['centre'] = $u->getCentre()->getName();
            }
            if($u->getDepartment()){
                $usersFormat[$counter]['department'] = $u->getDepartment()->getName();
            }
            if($u->getGroup()){
                $usersFormat[$counter]['group'] = $u->getGroup()->getName();
            }
            $usersFormat[$counter]['softwareSpecifications'] = $u->getSoftwareSpecifications();
            $usersFormat[$counter]['description'] = $u->getDescription();
            
            if($tmpFields){
                $usersFormat[$counter]['centre_tmp'] = $u->getCentreTmp();
                $usersFormat[$counter]['department_tmp'] = $u->getDepartmentTmp();
                $usersFormat[$counter]['groupName_tmp'] = $u->getGroupNameTmp();
                $usersFormat[$counter]['groupLeader_tmp'] = $u->getGroupLeaderTmp();
                $usersFormat[$counter]['groupCode_tmp'] = $u->getGroupCodeTmp();
                $usersFormat[$counter]['groupWebsite_tmp'] = $u->getGroupWebsiteTmp();
            }
            
            $counter++;
        }
        
        return $usersFormat;
    }
    
    public function getLinuxCommands($cmd, $userSelected, $actualUserContent = null){
        $command = '';
        
        switch($cmd){
            case 'distributionList':
                $cmd = 'Select email '.$userSelected->getEmail() ." > click on 'Borrar direcciones de emails seleccionados'";
                if($userSelected->getIsInDistributionList()){
                    $cmd = 'Add an user <b>'.$userSelected->getEmail().'</b>';
                    $cmd .= '<br><br>Search email <b>'.$userSelected->getEmail().'</b> in the list and click on it';
                    $cmd .= '<br><br>Nombre <b>'.$userSelected->getName().' '.$userSelected->getLastname()."</b> and click on <b>'Actualizar'</b>";
                }
                $command = "URL: http://listas.upo.es/wws/home";
                $command .= "<br><br>Click on <b>'admin' c3upo</b>";
                $command .= "<br><br>Click on <b>'administrador de subscriptores'</b>";
                $command .= "<br><br>$cmd";
                break;
                
            case 'status':
                $cmd = 'L';
                if($userSelected->getIsActive()){
                    $cmd = 'U';
                }
                $command = "$ sudo usermod -$cmd ".$userSelected->getUsername();
                break;
                
            case 'verification':
                $date = UsingTime::transformExpireDate($userSelected->getUsingTime());
                $command = '$ sudo useradd -m -e '.$date.' -c "'.$userSelected->getName().' '.$userSelected->getLastname()." &lt;".$userSelected->getEmail().'&gt;" -g '.$userSelected->getGroup()->getCode().' '.$userSelected->getUsername();
                $command .= '<br><br>$ sudo passwd '.$userSelected->getUsername();
                $command .= '<br><br>$ sudo chage -d0 '.$userSelected->getUsername();
                $command .= '<br><br>$ sudo make -C /var/yp';
                break;
            
            case 'edition':
                $actualTimingUse = $actualUserContent->getUsingTime();
                $actualUsername = $actualUserContent->getUsername();
                $actualName = $actualUserContent->getName();
                $actualLastname = $actualUserContent->getLastname();
                $actualEmail = $actualUserContent->getEmail();
                $actualGroup = $actualUserContent->getGroup();
                
                //to know the commands that we have to use in the cluster
                if($userSelected->getUsername() != $actualUsername){
                    $command .= '$ sudo usermod -l  '.$userSelected->getUsername().' '.$actualUsername;
                    $command .= '<br><br>$ sudo mv /home/'.$actualUsername.' /home/'.$userSelected->getUsername();
                    $command .= '<br><br>$ sudo usermod -d  /home/'.$userSelected->getUsername().' '.$userSelected->getUsername();
                }
                if($userSelected->getEmail() != $actualEmail || $userSelected->getName() != $actualName || $userSelected->getLastname() != $actualLastname){
                    $command .= ($command != '')?'<br><br>':'';
                    $command .= '$ sudo usermod -c "'.$userSelected->getName().' '.$userSelected->getLastname()." &lt;".$userSelected->getEmail().'&gt;" '.$userSelected->getUsername();
                }
                if($userSelected->getUsingTime() != $actualTimingUse){
                    $date = UsingTime::transformExpireDate($userSelected->getUsingTime());
                    $command .= ($command != '')?'<br><br>':'';
                    $command .= '$ sudo chage -E '.$date;
                }
                if($userSelected->getGroup()->getCode() != $actualGroup->getCode()){
                    $command .= ($command != '')?'<br><br>':'';
                    $command .= '$ sudo gpasswd -d '.$userSelected->getUserName().' '.$actualGroup->getCode();
                    $command .= '<br><br>$ sudo usermod -a -G '.$userSelected->getGroup()->getCode().' '.$userSelected->getUserName();
                }
                break;
        }
        
        return $command;
    }
    
}
