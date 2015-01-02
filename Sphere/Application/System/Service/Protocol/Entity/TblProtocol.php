<?php
namespace KREDA\Sphere\Application\System\Service\Protocol\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblProtocol")
 * @Cache(usage="READ_WRITE")
 */
class TblProtocol extends AbstractEntity
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $ProtocolDatabase;
    /**
     * @Column(type="integer")
     */
    protected $ProtocolTimestamp;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Account;
    /**
     * @Column(type="string")
     */
    protected $AccountUsername;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;
    /**
     * @Column(type="string")
     */
    protected $PersonFirstName;
    /**
     * @Column(type="string")
     */
    protected $PersonLastName;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Consumer;
    /**
     * @Column(type="string")
     */
    protected $ConsumerName;
    /**
     * @Column(type="string")
     */
    protected $ConsumerSuffix;
    /**
     * @Column(type="text")
     */
    protected $EntityFrom;
    /**
     * @Column(type="text")
     */
    protected $EntityTo;

    /**
     * @return string
     */
    public function getProtocolDatabase()
    {

        return $this->ProtocolDatabase;
    }

    /**
     * @param string $ProtocolDatabase
     */
    public function setProtocolDatabase( $ProtocolDatabase )
    {

        $this->ProtocolDatabase = $ProtocolDatabase;
    }

    /**
     * @return integer
     */
    public function getProtocolTimestamp()
    {

        return $this->ProtocolTimestamp;
    }

    /**
     * @param integer $ProtocolTimestamp
     */
    public function setProtocolTimestamp( $ProtocolTimestamp )
    {

        $this->ProtocolTimestamp = $ProtocolTimestamp;
    }

    /**
     * @return integer
     */
    public function getServiceGatekeeperAccount()
    {

        return $this->serviceGatekeeper_Account;
    }

    /**
     * @param integer $serviceGatekeeper_Account
     */
    public function setServiceGatekeeperAccount( $serviceGatekeeper_Account )
    {

        $this->serviceGatekeeper_Account = $serviceGatekeeper_Account;
    }

    /**
     * @return string
     */
    public function getAccountUsername()
    {

        return $this->AccountUsername;
    }

    /**
     * @param string $AccountUsername
     */
    public function setAccountUsername( $AccountUsername )
    {

        $this->AccountUsername = $AccountUsername;
    }

    /**
     * @return integer
     */
    public function getServiceManagementPerson()
    {

        return $this->serviceManagement_Person;
    }

    /**
     * @param integer $serviceManagement_Person
     */
    public function setServiceManagementPerson( $serviceManagement_Person )
    {

        $this->serviceManagement_Person = $serviceManagement_Person;
    }

    /**
     * @return string
     */
    public function getPersonFirstName()
    {

        return $this->PersonFirstName;
    }

    /**
     * @param string $PersonFirstName
     */
    public function setPersonFirstName( $PersonFirstName )
    {

        $this->PersonFirstName = $PersonFirstName;
    }

    /**
     * @return string
     */
    public function getPersonLastName()
    {

        return $this->PersonLastName;
    }

    /**
     * @param string $PersonLastName
     */
    public function setPersonLastName( $PersonLastName )
    {

        $this->PersonLastName = $PersonLastName;
    }

    /**
     * @return integer
     */
    public function getServiceGatekeeperConsumer()
    {

        return $this->serviceGatekeeper_Consumer;
    }

    /**
     * @param integer $serviceGatekeeper_Consumer
     */
    public function setServiceGatekeeperConsumer( $serviceGatekeeper_Consumer )
    {

        $this->serviceGatekeeper_Consumer = $serviceGatekeeper_Consumer;
    }

    /**
     * @return string
     */
    public function getConsumerName()
    {

        return $this->ConsumerName;
    }

    /**
     * @param string $ConsumerName
     */
    public function setConsumerName( $ConsumerName )
    {

        $this->ConsumerName = $ConsumerName;
    }

    /**
     * @return string
     */
    public function getConsumerSuffix()
    {

        return $this->ConsumerSuffix;
    }

    /**
     * @param string $ConsumerSuffix
     */
    public function setConsumerSuffix( $ConsumerSuffix )
    {

        $this->ConsumerSuffix = $ConsumerSuffix;
    }

    /**
     * @return string
     */
    public function getEntityFrom()
    {

        return $this->EntityFrom;
    }

    /**
     * @param string $EntityFrom
     */
    public function setEntityFrom( $EntityFrom )
    {

        $this->EntityFrom = $EntityFrom;
    }

    /**
     * @return string
     */
    public function getEntityTo()
    {

        return $this->EntityTo;
    }

    /**
     * @param string $EntityTo
     */
    public function setEntityTo( $EntityTo )
    {

        $this->EntityTo = $EntityTo;
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
