<?php
namespace KREDA\Sphere\Application\Management\Service\Address\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblAddressCity")
 * @Cache(usage="READ_ONLY")
 */
class TblAddressCity
{

    const ATTR_CODE = 'Code';
    const ATTR_NAME = 'Name';
    const ATTR_DISTRICT = 'District';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Code;
    /**
     * @Column(type="string")
     */
    private $Name;
    /**
     * @Column(type="string")
     */
    private $District;

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
    public function getCode()
    {

        return $this->Code;
    }

    /**
     * @param string $Code
     */
    public function setCode( $Code )
    {

        $this->Code = $Code;
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
     * @return string
     */
    public function getDistrict()
    {

        return $this->District;
    }

    /**
     * @param string $District
     */
    public function setDistrict( $District )
    {

        $this->District = $District;
    }

}
