<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPayment")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPayment extends AbstractEntity
{
    const ATTR_TBL_BANKING = 'tblBalance';

    /**
     * @Column(type="bigint")
     */
    protected $tblBalance;

    /**
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $Value;

    /**
     * @Column(type="date")
     */
    protected $Date;

    /**
     * @param null|TblBalance $tblBalance
     */
    public function setTblBalance($tblBalance = null)
    {
        $this->tblBalance = ( null === $tblBalance ? null : $tblBalance->getId() );
    }

    /**
     * @return bool|TblBalance
     */
    public function getTblBalance()
    {
        if (null === $this->tblBalance) {
            return false;
        } else {
            return Billing::serviceBalance()->entityBalanceById( $this->tblBalance );
        }
    }

    /**
     * @return string
     */
    public function getDate()
    {

        if (null === $this->Date) {
            return false;
        }
        /** @var \DateTime $Date */
        $Date = $this->Date;
        if ($Date instanceof \DateTime) {
            return $Date->format( 'd.m.Y' );
        } else {
            return (string)$Date;
        }
    }

    /**
     * @param \DateTime $Date
     */
    public function setDate(\DateTime $Date )
    {
        $this->Date = $Date;
    }

    /**
     * @param $value
     */
    public function setValue( $value )
    {
        $this->Value = $value;
    }

    public function getValue()
    {
        return $this->Value;
    }
}