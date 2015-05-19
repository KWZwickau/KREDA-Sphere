<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblItem")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblItem extends AbstractEntity
{
    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @Column(type="decimal")
     */
    protected $Price;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @return string
     */
    public function getDescription()
    {

        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription( $Description )
    {

        $this->Description = $Description;
    }

    /**
     * @return decimal
     */
    public function getPrice()
    {

        return $this->Price;
    }

    /**
     * @param decimal $Price
     */
    public function setPrice( $Price )
    {

        $this->Value = $Price;
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
