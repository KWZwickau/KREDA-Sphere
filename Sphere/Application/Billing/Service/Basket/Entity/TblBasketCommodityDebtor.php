<?php
namespace KREDA\Sphere\Application\Billing\Service\Basket\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblBasketCommodityDebtor")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblBasketCommodityDebtor extends AbstractEntity
{
    const ATTR_TBL_BASKET_COMMODITY = 'tblBasketCommodity';
    const ATTR_SERVICE_BILLING_DEBTOR = 'serviceBilling_Debtor';

    /**
     * @Column(type="bigint")
     */
    protected $tblBasketCommodity;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Debtor;

    /**
     * @param TblBasketCommodity $tblBasketCommodity
     */
    public function setTblBasketCommodity(TblBasketCommodity $tblBasketCommodity = null)
    {
        $this->tblBasketCommodity = ( null === $tblBasketCommodity ? null : $tblBasketCommodity->getId() );
    }

    /**
     * @return bool|TblBasketCommodity
     */
    public function getTblBasketCommodity()
    {
        if (null === $this->tblBasketCommodity)
        {
            return false;
        } else {
            return Billing::serviceBasket()->entityBasketCommodityById( $this->tblBasketCommodity );
        }
    }

    /**
     * @param null|TblDebtor $tblDebtor
     */
    public function setServiceBillingDebtor(TblDebtor $tblDebtor = null)
    {
        $this->serviceBilling_Debtor = ( null === $tblDebtor ? null : $tblDebtor->getId() );
    }

    /**
     * @return bool|TblDebtor
     */
    public function getServiceBillingDebtor()
    {
        if (null === $this->serviceBilling_Debtor) {
            return false;
        } else {
            return Billing::serviceBanking()->entityDebtorById( $this->serviceBilling_Debtor );
        }
    }
}