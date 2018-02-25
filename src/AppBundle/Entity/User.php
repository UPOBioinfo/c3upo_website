<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User. Entity used to store the users reigstered in the Registration form.
 * Those are the users that requires access to the cluster.
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="idx_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email",message="email.exist")
 */
class User 
{
    
   protected $captchaCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Assert\Length(max=100)
     * @Assert\NotBlank(message="name.empty")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=false)
     * @Assert\Length(max=100)
     * @Assert\NotBlank(message="lastname.empty")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="phone.empty")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="email.empty")
     * @Assert\Email(message="email.invalid")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $username;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Centre", inversedBy="registeredUsers")
     * @ORM\JoinColumn(name="centre", referencedColumnName="id")
     */
    private $centre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="centre_tmp", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $centre_tmp;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="registeredUsers")
     * @ORM\JoinColumn(name="department", referencedColumnName="id")
     */
    private $department;
    
    /**
     * @var string
     *
     * @ORM\Column(name="department_tmp", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $department_tmp;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="registeredUsers")
     * @ORM\JoinColumn(name="grouppe", referencedColumnName="id")
     */
    private $group;
            
    /**
     * @var string
     *
     * @ORM\Column(name="groupName_tmp", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $groupName_tmp;
    
    /**
     * @var string
     *
     * @ORM\Column(name="groupCode_tmp", type="string", length=255, nullable=true)
     * @Assert\Length(max=12, maxMessage="groupCode.maxLength")
     */
    private $groupCode_tmp;
    
    /**
     * @var string
     *
     * @ORM\Column(name="groupWebsite_tmp", type="string", length=255, nullable=true)
     */
    private $groupWebsite_tmp;
    
    /**
     * @var string
     *
     * @ORM\Column(name="groupLeader_tmp", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $groupLeader_tmp;
    
    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="position.empty")
     */
    private $position;
    
    /**
     * @var string
     *
     * @ORM\Column(name="softwareSpecifications", type="text", length=255, nullable=true)
     */
    private $softwareSpecifications;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isClusterUser", type="boolean", nullable=false, options={"default" = "0"})
     */
    private $isClusterUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean", nullable=false, options={"default" = "0"})
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isInDistributionList", type="boolean", nullable=false, options={"default" = "0"})
     */
    private $isInDistributionList;
     
    /**
     * @var datetime
     *
     * @ORM\Column(name="registerdate", type="datetime", nullable=true)
     */
    private $registerdate;    
    
    /**
     * @ORM\ManyToOne(targetEntity="UsingTime", inversedBy="registeredUsers")
     * @ORM\JoinColumn(name="usingTime", referencedColumnName="id")
     */
    private $usingTime;
    
    /**
     * @var string
     *
     * @ORM\Column(name="editor", type="string", length=50, nullable=true)
     * @Assert\Length(max=50)
     */
    private $editor;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="edited", type="datetime", nullable=true)
     */
    private $edited;

    /**
     * @var string
     *
     * @ORM\Column(name="deletor", type="string", length=50, nullable=true)
     * @Assert\Length(max=50)
     */
    private $deletor;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    private $deleted;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set centreTmp
     *
     * @param string $centreTmp
     *
     * @return User
     */
    public function setCentreTmp($centreTmp)
    {
        $this->centre_tmp = $centreTmp;

        return $this;
    }

    /**
     * Get centreTmp
     *
     * @return string
     */
    public function getCentreTmp()
    {
        return $this->centre_tmp;
    }

    /**
     * Set departmentTmp
     *
     * @param string $departmentTmp
     *
     * @return User
     */
    public function setDepartmentTmp($departmentTmp)
    {
        $this->department_tmp = $departmentTmp;

        return $this;
    }

    /**
     * Get departmentTmp
     *
     * @return string
     */
    public function getDepartmentTmp()
    {
        return $this->department_tmp;
    }

    /**
     * Set groupNameTmp
     *
     * @param string $groupNameTmp
     *
     * @return User
     */
    public function setGroupNameTmp($groupNameTmp)
    {
        $this->groupName_tmp = $groupNameTmp;

        return $this;
    }

    /**
     * Get groupNameTmp
     *
     * @return string
     */
    public function getGroupNameTmp()
    {
        return $this->groupName_tmp;
    }

    /**
     * Set groupCodeTmp
     *
     * @param string $groupCodeTmp
     *
     * @return User
     */
    public function setGroupCodeTmp($groupCodeTmp)
    {
        $this->groupCode_tmp = $groupCodeTmp;

        return $this;
    }

    /**
     * Get groupCodeTmp
     *
     * @return string
     */
    public function getGroupCodeTmp()
    {
        return $this->groupCode_tmp;
    }

    /**
     * Set groupWebsiteTmp
     *
     * @param string $groupWebsiteTmp
     *
     * @return User
     */
    public function setGroupWebsiteTmp($groupWebsiteTmp)
    {
        $this->groupWebsite_tmp = $groupWebsiteTmp;

        return $this;
    }

    /**
     * Get groupWebsiteTmp
     *
     * @return string
     */
    public function getGroupWebsiteTmp()
    {
        return $this->groupWebsite_tmp;
    }

    /**
     * Set groupLeaderTmp
     *
     * @param string $groupLeaderTmp
     *
     * @return User
     */
    public function setGroupLeaderTmp($groupLeaderTmp)
    {
        $this->groupLeader_tmp = $groupLeaderTmp;

        return $this;
    }

    /**
     * Get groupLeaderTmp
     *
     * @return string
     */
    public function getGroupLeaderTmp()
    {
        return $this->groupLeader_tmp;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set softwareSpecifications
     *
     * @param string $softwareSpecifications
     *
     * @return User
     */
    public function setSoftwareSpecifications($softwareSpecifications)
    {
        $this->softwareSpecifications = $softwareSpecifications;

        return $this;
    }

    /**
     * Get softwareSpecifications
     *
     * @return string
     */
    public function getSoftwareSpecifications()
    {
        return $this->softwareSpecifications;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isClusterUser
     *
     * @param boolean $isClusterUser
     *
     * @return User
     */
    public function setIsClusterUser($isClusterUser)
    {
        $this->isClusterUser = $isClusterUser;

        return $this;
    }

    /**
     * Get isClusterUser
     *
     * @return boolean
     */
    public function getIsClusterUser()
    {
        return $this->isClusterUser;
    }

    /**
     * Set registerdate
     *
     * @param \DateTime $registerdate
     *
     * @return User
     */
    public function setRegisterdate($registerdate)
    {
        $this->registerdate = $registerdate;

        return $this;
    }

    /**
     * Get registerdate
     *
     * @return \DateTime
     */
    public function getRegisterdate()
    {
        return $this->registerdate;
    }

    /**
     * Set centre
     *
     * @param \AppBundle\Entity\Centre $centre
     *
     * @return User
     */
    public function setCentre(\AppBundle\Entity\Centre $centre = null)
    {
        $this->centre = $centre;

        return $this;
    }

    /**
     * Get centre
     *
     * @return \AppBundle\Entity\Centre
     */
    public function getCentre()
    {
        return $this->centre;
    }

    /**
     * Set department
     *
     * @param \AppBundle\Entity\Department $department
     *
     * @return User
     */
    public function setDepartment(\AppBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \AppBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return User
     */
    public function setGroup(\AppBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
    
    public function getCaptchaCode()
    {
      return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
      $this->captchaCode = $captchaCode;
    }

    /**
     * Set usingTime
     *
     * @param \AppBundle\Entity\UsingTime $usingTime
     *
     * @return User
     */
    public function setUsingTime(\AppBundle\Entity\UsingTime $usingTime = null)
    {
        $this->usingTime = $usingTime;

        return $this;
    }

    /**
     * Get usingTime
     *
     * @return \AppBundle\Entity\UsingTime
     */
    public function getUsingTime()
    {
        return $this->usingTime;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set editor
     *
     * @param string $editor
     *
     * @return User
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return string
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set edited
     *
     * @param \DateTime $edited
     *
     * @return User
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited
     *
     * @return \DateTime
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Set deletor
     *
     * @param string $deletor
     *
     * @return User
     */
    public function setDeletor($deletor)
    {
        $this->deletor = $deletor;

        return $this;
    }

    /**
     * Get deletor
     *
     * @return string
     */
    public function getDeletor()
    {
        return $this->deletor;
    }

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     *
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set isInDistributionList
     *
     * @param boolean $isInDistributionList
     *
     * @return User
     */
    public function setIsInDistributionList($isInDistributionList)
    {
        $this->isInDistributionList = $isInDistributionList;

        return $this;
    }

    /**
     * Get isInDistributionList
     *
     * @return boolean
     */
    public function getIsInDistributionList()
    {
        return $this->isInDistributionList;
    }
}
