<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblConsumerTypeList")
 */
class TblConsumerTypeList extends AbstractEntity
{

    const ATTR_TBL_CONSUMER_TYPE = 'tblConsumerType';
    const ATTR_TBL_CONSUMER = 'tblConsumer';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="bigint")
     */
    protected $tblConsumerType;
    /**
     * @Column(type="bigint")
     */
    protected $tblConsumer;

    /**
     * @return bool|TblConsumerType
     */
    public function getTblConsumerType()
    {

        if (null === $this->tblConsumerType) {
            return false;
        } else {
            return Gatekeeper::serviceConsumer()->entityConsumerTypeById( $this->tblConsumerType );
        }
    }

    /**
     * @param null|TblConsumerType $tblConsumerType
     */
    public function setTblConsumerType( TblConsumerType $tblConsumerType = null )
    {

        $this->tblConsumerType = ( null === $tblConsumerType ? null : $tblConsumerType->getId() );
    }

    /**
     * @return bool|TblConsumer
     */
    public function getTblConsumer()
    {

        if (null === $this->tblConsumer) {
            return false;
        } else {
            return Gatekeeper::serviceConsumer()->entityConsumerById( $this->tblConsumer );
        }
    }

    /**
     * @param null|TblConsumer $tblConsumer
     */
    public function setTblConsumer( TblConsumer $tblConsumer = null )
    {

        $this->tblConsumer = ( null === $tblConsumer ? null : $tblConsumer->getId() );
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
