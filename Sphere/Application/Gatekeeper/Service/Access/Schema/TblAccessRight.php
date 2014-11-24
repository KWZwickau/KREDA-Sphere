<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblAccessRight")
 */
class TblAccessRight
{

    const ATTR_ROUTE = 'Route';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Route;

    /**
     * @param string $Route
     */
    function __construct( $Route )
    {

        $this->Route = $Route;
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
    public function getRoute()
    {

        return $this->Route;
    }

    /**
     * @param string $Route
     */
    public function setRoute( $Route )
    {

        $this->Route = $Route;
    }
}
