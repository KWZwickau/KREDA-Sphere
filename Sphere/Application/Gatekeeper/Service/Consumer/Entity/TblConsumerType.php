<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblConsumerType")
 */
class TblConsumerType extends AbstractEntity
{

    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @return integer
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param integer $Name
     */
    public function setName( $Name )
    {

        $this->Name = $Name;
    }
}
