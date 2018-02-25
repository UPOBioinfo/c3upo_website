<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of UsingTime. It is to know for how long the user is registering to use the cluster
 *
 * @author Sofia Moreno Oñate <sofmorona@gmail.com>
 */
/**
 *
 * @ORM\Entity()
 * @ORM\Table(name="usingTime")
 */
class UsingTime {
    
    CONST UNLIMITED = 'UNLIMITED';
    CONST MONTH1 = '1_MONTH';
    CONST MONTH6 = '6_MONTHS';
    CONST YEAR1 = '1_YEAR';
    CONST YEAR4 = '4_YEAR';
    
    
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
     * @ORM\Column(name="code", type="string", length=150, nullable=false)
     */
    private $code;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="usingTime")
     */
    private $registeredUsers;

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
     * Set code
     *
     * @param string $code
     *
     * @return UsingTime
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
     * Constructor
     */
    public function __construct()
    {
        $this->registeredUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add registeredUser
     *
     * @param \AppBundle\Entity\User $registeredUser
     *
     * @return UsingTime
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
    
    public static function transformExpireDate($timing){
        $actualDate = new \DateTime();
        switch($timing->getCode()){
            case self::UNLIMITED:
                return -1;
                
            case self::MONTH1:
                $actualDate->add(new \DateInterval('P1M'));
                return $actualDate->format('Y-m-d');
                
            case self::MONTH6:
                $actualDate->add(new \DateInterval('P6M'));
                return $actualDate->format('Y-m-d');
                
            case self::YEAR1:
                $actualDate->add(new \DateInterval('P1Y'));
                return $actualDate->format('Y-m-d');
                
            case self::YEAR4:
                $actualDate->add(new \DateInterval('P4Y'));
                return $actualDate->format('Y-m-d');
        }
        
    }
}
