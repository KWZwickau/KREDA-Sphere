<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education;

/**
 * @Entity
 * @Table(name="tblGroup")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblGroup
{

    const ATTR_NAME = 'Name';
    const ATTR_TBL_LEVEL = 'tblLevel';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Name;
    /**
     * @Column(type="bigint")
     */
    private $tblLevel;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
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
     * @return bool|TblLevel
     */
    public function getTblLevel()
    {

        return Management::serviceEducation()->entityLevelById( $this->tblLevel );
    }

    /**
     * @param null|TblLevel $tblLevel
     */
    public function setTblLevel( TblLevel $tblLevel = null )
    {

        $this->tblLevel = ( null === $tblLevel ? null : $tblLevel->getId() );
    }
}
