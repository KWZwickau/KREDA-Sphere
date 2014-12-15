<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;

/**
 * @Entity
 * @Table(name="tblConsumer")
 * @Cache(usage="READ_ONLY")
 */
class TblConsumer
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
     * @Column(type="string")
     */
    private $TableSuffix;
    /**
     * @Column(type="string")
     */
    private $DatabaseSuffix;
    /**
     * @Column(type="bigint")
     */
    private $serviceManagement_Address;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getDatabaseSuffix()
    {

        return $this->DatabaseSuffix;
    }

    /**
     * @param string $DatabaseSuffix
     */
    public function setDatabaseSuffix( $DatabaseSuffix )
    {

        $this->DatabaseSuffix = $DatabaseSuffix;
    }

    /**
     * @return string
     */
    public function getTableSuffix()
    {

        return $this->TableSuffix;
    }

    /**
     * @param string $TableSuffix
     */
    public function setTableSuffix( $TableSuffix )
    {

        $this->TableSuffix = $TableSuffix;
    }

    /**
     * @return bool|TblAddress
     */
    public function getServiceManagementAddress()
    {

        return Management::serviceAddress()->entityAddressById( $this->serviceManagement_Address );
    }

    /**
     * @param null|TblAddress $tblAddress
     */
    public function setServiceManagementAddress( TblAddress $tblAddress = null )
    {

        $this->serviceManagement_Address = ( null === $tblAddress ? null : $tblAddress->getId() );
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
