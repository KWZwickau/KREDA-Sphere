<?php
namespace KREDA\Sphere\Application\Graduation\Service\Grade\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
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

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Acronym;
    /**
     * @Column(type="string")
     */
    protected $Name;

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
