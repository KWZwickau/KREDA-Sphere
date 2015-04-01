<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccessRight")
 */
class TblAccessRight extends AbstractEntity
{

    const ATTR_ROUTE = 'Route';

    /**
     * @Column(type="string")
     */
    protected $Route;

    /**
     * @param string $Route
     */
    function __construct( $Route )
    {

        $this->Route = $Route;
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
