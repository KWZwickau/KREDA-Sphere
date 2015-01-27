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
 * @Table(name="tblConsumerTypList")
 */
class TblConsumerTypList extends AbstractEntity
{

    const ATTR_TBL_CONSUMER_TYP = 'tblConsumerTyp';
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
    protected $tblConsumerTyp;
    /**
     * @Column(type="bigint")
     */
    protected $tblConsumer;

    /**
     * @return bool|TblConsumerTyp
     */
    public function getTblConsumerTyp()
    {

        if (null === $this->tblConsumerTyp) {
            return false;
        } else {
            return Gatekeeper::serviceConsumer()->entityConsumerTypById( $this->tblConsumerTyp );
        }
    }

    /**
     * @param null|TblConsumerTyp $tblConsumerTyp
     */
    public function setTblConsumerTyp( TblConsumerTyp $tblConsumerTyp = null )
    {

        $this->tblConsumerTyp = ( null === $tblConsumerTyp ? null : $tblConsumerTyp->getId() );
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
