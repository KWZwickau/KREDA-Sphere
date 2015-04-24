<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblSubject")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubject extends AbstractEntity
{

    const ATTR_NAME = 'Name';
    const ATTR_ACRONYM = 'Acronym';
    const ATTR_ACTIVE_STATE = 'ActiveState';

    /**
     * @Column(type="string")
     */
    protected $Acronym;
    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="boolean")
     */
    protected $ActiveState = true;

    /**
     * @param string $Acronym
     */
    function __construct( $Acronym )
    {

        $this->Acronym = $Acronym;
    }

    /**
     * @return string
     */
    public function getAcronym()
    {

        return $this->Acronym;
    }

    /**
     * @param string $Acronym
     */
    public function setAcronym( $Acronym )
    {

        $this->Acronym = $Acronym;
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

    /**
     * @return boolean
     */
    public function getActiveState()
    {

        return $this->ActiveState;
    }

    /**
     * @param boolean $ActiveState
     */
    public function setActiveState( $ActiveState )
    {

        $this->ActiveState = $ActiveState;
    }
}
