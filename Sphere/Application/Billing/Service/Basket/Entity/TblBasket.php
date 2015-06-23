<?php
namespace KREDA\Sphere\Application\Billing\Service\Basket\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblBasket")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblBasket extends AbstractEntity
{
    /**
     * @Column(type="datetime")
     */
    protected $CreateDate;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @param string $Name
     */
    public function setName($Name)
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
     * @return string
     */
    public function getCreateDate()
    {

        if (null === $this->CreateDate) {
            return false;
        }
        /** @var \DateTime $CreateDate */
        $CreateDate = $this->CreateDate;
        if ($CreateDate instanceof \DateTime) {
            return $CreateDate->format( 'd.m.Y H:i:s' );
        } else {
            return (string)$CreateDate;
        }
    }

    /**
     * @param \DateTime $CreateDate
     */
    public function setCreateDate(\DateTime $CreateDate )
    {
        $this->CreateDate = $CreateDate;
    }
}
