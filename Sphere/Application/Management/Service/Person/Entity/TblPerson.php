<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblPerson")
 * @Cache(usage="READ_ONLY")
 */
class TblPerson
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;

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
