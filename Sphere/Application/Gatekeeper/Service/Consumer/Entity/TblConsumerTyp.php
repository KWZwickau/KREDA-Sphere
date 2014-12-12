<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblConsumerTyp")
 */
class TblConsumerTyp
{

    const ATTR_NAME = 'Name';

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

}
