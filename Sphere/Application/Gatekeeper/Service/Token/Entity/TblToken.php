<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblToken")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblToken extends AbstractEntity
{

    const ATTR_IDENTIFIER = 'Identifier';
    const ATTR_SERVICE_GATEKEEPER_CONSUMER = 'serviceGatekeeper_Consumer';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Identifier;
    /**
     * @Column(type="string")
     */
    protected $Serial;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Consumer;

    /**
     * @param string $Identifier
     */
    function __construct( $Identifier )
    {

        $this->Identifier = $Identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {

        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    public function setIdentifier( $Identifier )
    {

        $this->Identifier = $Identifier;
    }

    /**
     * @return string
     */
    public function getSerial()
    {

        return $this->Serial;
    }

    /**
     * @param string $Serial
     */
    public function setSerial( $Serial )
    {

        $this->Serial = $Serial;
    }

    /**
     * @return bool|TblConsumer
     */
    public function getServiceGatekeeperConsumer()
    {

        if (null === $this->serviceGatekeeper_Consumer) {
            return false;
        } else {
            return Gatekeeper::serviceConsumer()->entityConsumerById( $this->serviceGatekeeper_Consumer );
        }
    }

    /**
     * @param null|TblConsumer $tblConsumer
     */
    public function setServiceGatekeeperConsumer( TblConsumer $tblConsumer = null )
    {

        $this->serviceGatekeeper_Consumer = ( null === $tblConsumer ? null : $tblConsumer->getId() );
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
