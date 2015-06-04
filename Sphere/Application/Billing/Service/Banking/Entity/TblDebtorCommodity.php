<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblDebtorCommodity")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblDebtorCommodity extends AbstractEntity
{

    const ATTR_SERVICE_BILLING_COMMODITY = 'serviceBilling_Commodity';
    const ATTR_TBL_DEBTOR = 'tblDebtor';

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Commodity;
    /**
     * @Column(type="bigint")
     */
    protected $tblDebtor;

    /**
     * @return bool|TblCommodity
     */
    public function getServiceBillingCommodity()
    {

        if (null === $this->serviceBilling_Commodity) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityCommodityById( $this->serviceBilling_Commodity );
        }
    }

    /**
     * @param null|TblCommodity $tblCommodity
     */
    public function setServiceBillingCommodity( TblCommodity $tblCommodity )
    {

        $this->serviceBilling_Commodity = ( null === $tblCommodity ? null : $tblCommodity->getId() );
    }

    /**
     * @return bool|TblDebtor
     */
    public function getTblDebtor()
    {

        if (null === $this->tblDebtor) {
            return false;
        } else {
            return Billing::serviceBanking()->entityDebtorById( $this->tblDebtor );
        }
    }

    /**
     * @param null|TblDebtor $tblDebtor
     */
    public function setTblDebtor( TblDebtor $tblDebtor )
    {

        $this->tblDebtor = ( null === $tblDebtor ? null : $tblDebtor->getId() );
    }

}
