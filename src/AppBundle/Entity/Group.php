<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Group
 *
 * @author Sofia Moreno Oñate <sofmorona@gmail.com>
 */
/**
 *
 * @ORM\Entity()
 * @ORM\Table(name="grouppe")
 */
class Group {
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="LoginUser", inversedBy="groups")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     */
    private $creator;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     * @Assert\Length(max=100)
     * @Assert\NotBlank(message = "groupName.empty")
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=150, nullable=false, unique=true)
     * @Assert\Length(max=12, maxMessage="groupCode.maxLength")
     * @Assert\NotBlank(message = "groupCode.empty")
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;
    
    /**
     * @var string
     * 
     * @ORM\ManyToOne(targetEntity="Leader", inversedBy="group")
     * @ORM\JoinColumn(name="leader", referencedColumnName="id")
     */
    private $leader;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    private $registeredUsers;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registeredUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Group
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set editor
     *
     * @param string $editor
     *
     * @return Group
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
     * @return Group
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
     * @return Group
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
     * @return Group
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
     * Set name
     *
     * @param string $name
     *
     * @return Group
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
     * Set code
     *
     * @param string $code
     *
     * @return Group
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Group
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\LoginUser $creator
     *
     * @return Group
     */
    public function setCreator(\AppBundle\Entity\LoginUser $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\LoginUser
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add registeredUser
     *
     * @param \AppBundle\Entity\User $registeredUser
     *
     * @return Group
     */
    public function addRegisteredUser(\AppBundle\Entity\User $registeredUser)
    {
        $this->registeredUsers[] = $registeredUser;

        return $this;
    }

    /**
     * Remove registeredUser
     *
     * @param \AppBundle\Entity\User $registeredUser
     */
    public function removeRegisteredUser(\AppBundle\Entity\User $registeredUser)
    {
        $this->registeredUsers->removeElement($registeredUser);
    }

    /**
     * Get registeredUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegisteredUsers()
    {
        return $this->registeredUsers;
    }

    /**
     * Set leader
     *
     * @param \AppBundle\Entity\Leader $leader
     *
     * @return Group
     */
    public function setLeader(\AppBundle\Entity\Leader $leader = null)
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * Get leader
     *
     * @return \AppBundle\Entity\Leader
     */
    public function getLeader()
    {
        return $this->leader;
    }
}
