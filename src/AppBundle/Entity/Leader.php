<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Centre
 *
 * @author Sofia Moreno Oñate <sofmorona@gmail.com>
 */
/**
 *
 * @ORM\Entity()
 * @ORM\Table(name="leader")
 */
class Leader {
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
     * @ORM\ManyToOne(targetEntity="LoginUser", inversedBy="leaders")
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
     * @Assert\Length(max=150)
     * @Assert\NotBlank(message = "leader.name.empty")
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="leader")
     */
    private $group;
    

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
     * @return Leader
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
     * @return Leader
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
     * @return Leader
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
     * @return Leader
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
     * @return Leader
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
     * @return Leader
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
     * Set creator
     *
     * @param \AppBundle\Entity\LoginUser $creator
     *
     * @return Leader
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
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return Leader
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
}
