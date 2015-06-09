<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblReference")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblReference extends AbstractEntity
{
    const ATTR_TBL_DEBTOR = "tblDebtor";
    const ATTR_IS_VOID = "IsVoid";

    /**
     * @Column(type="string")
     */
    protected $Reference;
    /**
     * @Column(type="boolean")
     */
    protected $IsVoid;
    /**
     * @Column(type="bigint")
     */
    protected $tblDebtor;

    /**
     * @return string $Reference
     */
    public function getReference()
    {
        return $this->Reference;
    }

    /**
     * @param string $reference
     */
    public function setReference( $reference )
    {
        $this->Reference = $reference;
    }

    /**
     * @return boolean $IsVoid
     */
    public function getIsVoid()
    {
        return $this->IsVoid;
    }

    /**
     * @param boolean $isVoid
     */
    public function setIsVoid ( $isVoid )
    {
        $this->IsVoid = $isVoid;
    }

    /**
     * @return bool|TblDebtor
     */
    public function getServiceBillingBanking()
    {
        if (null === $this->getServiceBillingBanking()) {
            return false;
        } else {
            return Billing::serviceBanking()->entityDebtorById( $this->getServiceBillingBanking() );
        }
    }

    /**
     * @param null|TblDebtor $serviceTblDebtor
     */
    public function setServiceTblDebtor( TblDebtor $serviceTblDebtor)
    {
        $this->tblDebtor = (null === $serviceTblDebtor ? null : $serviceTblDebtor->getId() );
    }

}