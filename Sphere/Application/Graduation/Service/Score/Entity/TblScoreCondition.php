<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreCondition")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreCondition extends AbstractEntity
{

    const ATTR_NAME = 'Name';
    const ATTR_ROUND = 'Round';
    const ATTR_PRIORITY = 'Priority';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="boolean")
     */
    protected $Round;
    /**
     * @Column(type="integer")
     */
    protected $Priority;

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
     * @return integer
     */
    public function getPriority()
    {

        return $this->Priority;
    }

    /**
     * @param integer $Priority
     */
    public function setPriority( $Priority )
    {

        $this->Priority = $Priority;
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
