<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccountRole")
 */
class TblAccountRole extends AbstractEntity
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
