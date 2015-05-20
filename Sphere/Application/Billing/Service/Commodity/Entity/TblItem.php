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
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $Price;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $CostUnit;

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
     * @return (type="decimal", precision=14, scale=4)
     */
    public function getPrice()
    {

        return $this->Price;
    }

    /**
     * @param (type="decimal", precision=14, scale=4) $Price
     */
    public function setPrice( $Price )
    {
        $this->Price = $Price;
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
    public function getCostUnit()
    {

        return $this->CostUnit;
    }

    /**
     * @param string $CostUnit
     */
    public function setCostUnit( $CostUnit )
    {

        $this->CostUnit = $CostUnit;
    }
}
