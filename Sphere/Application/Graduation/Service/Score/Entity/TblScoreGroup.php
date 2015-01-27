<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreGroup extends AbstractEntity
{

    const ATTR_NAME = 'Name';
    const ATTR_ROUND = 'Round';
    const ATTR_MULTIPLIER = 'Multiplier';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="boolean")
     */
    protected $Round;
    /**
     * @Column(type="float")
     */
    protected $Multiplier;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
    }

    /**
     * @return boolean
     */
    public function getRound()
    {

        return $this->Round;
    }

    /**
     * @param boolean $Round
     */
    public function setRound( $Round )
    {

        $this->Round = $Round;
    }

    /**
     * @return float
     */
    public function getMultiplier()
    {

        return $this->Multiplier;
    }

    /**
     * @param float $Multiplier
     */
    public function setMultiplier( $Multiplier )
    {

        $this->Multiplier = $Multiplier;
    }

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName( $Name )
    {

        $this->Name = $Name;
    }
}
