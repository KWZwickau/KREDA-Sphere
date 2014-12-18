<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(readOnly=true)
 * @Table(name="viewConsumer")
 * @Cache(usage="READ_ONLY")
 */
class ViewConsumer
{

    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblConsumer;
    /**
     * @Column(type="string")
     */
    private $ConsumerName;
    /**
     * @Column(type="string")
     */
    private $ConsumerDatabaseSuffix;
    /**
     * @Column(type="string")
     */
    private $ConsumerTableSuffix;
    /**
     * @Column(type="bigint")
     */
    private $serviceManagement_Address;
    /**
     * @Column(type="bigint")
     */
    private $tblConsumerTypList;
    /**
     * @Column(type="bigint")
     */
    private $tblConsumerTyp;
    /**
     * @Column(type="string")
     */
    private $TypName;

    /**
     * @return string
     */
    public function getTypName()
    {

        return $this->TypName;
    }

    /**
     * @return string
     */
    public function getConsumerName()
    {

        return $this->ConsumerName;
    }

    /**
     * @return string
     */
    public function getConsumerDatabaseSuffix()
    {

        return $this->ConsumerDatabaseSuffix;
    }

    /**
     * @return string
     */
    public function getConsumerTableSuffix()
    {

        return $this->ConsumerTableSuffix;
    }

    /**
     * @return integer
     */
    public function getTblConsumerTyp()
    {

        return $this->tblConsumerTyp;
    }

    /**
     * @return integer
     */
    public function getTblConsumerTypList()
    {

        return $this->tblConsumerTypList;
    }

    /**
     * @return integer
     */
    public function getTblConsumer()
    {

        return $this->tblConsumer;
    }

    /**
     * @return integer
     */
    public function getServiceManagementAddress()
    {

        return $this->serviceManagement_Address;
    }
}
