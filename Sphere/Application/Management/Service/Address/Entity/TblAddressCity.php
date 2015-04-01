<?php
namespace KREDA\Sphere\Application\Management\Service\Address\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAddressCity")
 * @Cache(usage="READ_ONLY")
 */
class TblAddressCity extends AbstractEntity
{

    const ATTR_CODE = 'Code';
    const ATTR_NAME = 'Name';
    const ATTR_DISTRICT = 'District';

    /**
     * @Column(type="string")
     */
    protected $Code;
    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $District;

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
