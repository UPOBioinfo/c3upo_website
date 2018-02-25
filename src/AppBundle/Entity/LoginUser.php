<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="loginUser", uniqueConstraints={@ORM\UniqueConstraint(name="idx_username", columns={"username"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LoginUserRepository")
 * @UniqueEntity("username",message="username.exist")
 */
class LoginUser implements AdvancedUserInterface, \Serializable 
{
    
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="email.empty")
     * @Assert\Email(message="email.invalid")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="username.empty")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\NotBlank(message="password.empty")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     * @ORM\JoinColumn(name="roleId", referencedColumnName="id")
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isactive", type="boolean", nullable=false)
     */
    private $isactive;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", options={"default" = "BdODmKWjGtaKeFyYsNCbOPRzquNIIRMiEFPjqTSgbfMvMeZgNKihEdUdUXUniHUh"}, length=255, nullable=false)
     */
    private $salt;
    
    /**
     * @ORM\OneToMany(targetEntity="Centre", mappedBy="creator")
     */
    private $centres;
    
    /**
     * @ORM\OneToMany(targetEntity="Department", mappedBy="creator")
     */
    private $departments;
    
    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="creator")
     */
    private $groups;
    
    /**
     * @ORM\OneToMany(targetEntity="Leader", mappedBy="creator")
     */
    private $leaders;
    
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
     * @return LoginUser
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
     * @return LoginUser
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
     * Set email
     *
     * @param string $email
     *
     * @return LoginUser
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
     * @return LoginUser
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
     * Set password
     *
     * @param string $password
     *
     * @return LoginUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isactive
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isactive
        ) = unserialize($serialized);
    }

    
    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array($this->role->getRole());
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return User
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    public function getSalt() {
        return $this->salt;
    }
    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get isactive
     *
     * @return boolean 
     */
    public function getIsactive()
    {
        return $this->isactive;
    }
    
    /**
     * Set isactive
     *
     * @param boolean $isactive
     * @return User
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }
    
    public function isEnabled() {
        return $this->isactive;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->departments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add centre
     *
     * @param \AppBundle\Entity\Centre $centre
     *
     * @return LoginUser
     */
    public function addCentre(\AppBundle\Entity\Centre $centre)
    {
        $this->centres[] = $centre;

        return $this;
    }

    /**
     * Remove centre
     *
     * @param \AppBundle\Entity\Centre $centre
     */
    public function removeCentre(\AppBundle\Entity\Centre $centre)
    {
        $this->centres->removeElement($centre);
    }

    /**
     * Get centres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentres()
    {
        return $this->centres;
    }

    /**
     * Add department
     *
     * @param \AppBundle\Entity\Department $department
     *
     * @return LoginUser
     */
    public function addDepartment(\AppBundle\Entity\Department $department)
    {
        $this->departments[] = $department;

        return $this;
    }

    /**
     * Remove department
     *
     * @param \AppBundle\Entity\Department $department
     */
    public function removeDepartment(\AppBundle\Entity\Department $department)
    {
        $this->departments->removeElement($department);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Add group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return LoginUser
     */
    public function addGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \AppBundle\Entity\Group $group
     */
    public function removeGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add leader
     *
     * @param \AppBundle\Entity\Leader $leader
     *
     * @return LoginUser
     */
    public function addLeader(\AppBundle\Entity\Leader $leader)
    {
        $this->leaders[] = $leader;

        return $this;
    }

    /**
     * Remove leader
     *
     * @param \AppBundle\Entity\Leader $leader
     */
    public function removeLeader(\AppBundle\Entity\Leader $leader)
    {
        $this->leaders->removeElement($leader);
    }

    /**
     * Get leaders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeaders()
    {
        return $this->leaders;
    }
}
