<?php
namespace KREDA\Sphere\Application\Graduation\Service\Grade\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblGradeType")
 * @Cache(usage="READ_ONLY")
 */
class TblGradeType extends AbstractEntity
{

    const ATTR_ACRONYM = 'Acronym';
    const ATTR_NAME = 'Name';
    const ATTR_ACTIVE = 'Active';

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
    protected $Active;

    /**
     * @param string $Acronym
     */
    function __construct( $Acronym )
    {

        $this->Acronym = $Acronym;
        $this->Active = true;
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

        return $this->Active;
    }

    /**
     * @param boolean $Active
     */
    public function setActiveState( $Active )
    {

        $this->Active = $Active;
    }
}
