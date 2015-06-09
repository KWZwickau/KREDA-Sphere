<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblTempInvoiceCommodity")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblTempInvoiceCommodity extends AbstractEntity
{
    const ATTR_TBL_TEMP_INVOICE = 'tblTempInvoice';
    const ATTR_SERVICE_BILLING_COMMODITY = 'serviceBilling_Commodity';

    /**
     * @Column(type="bigint")
     */
    protected $tblTempInvoice;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Commodity;

    /**
     * @return bool|TblTempInvoice
     */
    public function getTblTempInvoice()
    {
        if (null === $this->tblTempInvoice) {
            return false;
        } else {
            return Billing::serviceInvoice()->entityTempInvoiceById($this->tblTempInvoice);
        }
    }

    /**
     * @param TblTempInvoice $tblTempInvoice
     */
    public function setTblTempInvoice( TblTempInvoice $tblTempInvoice = null )
    {
        $this->tblTempInvoice = ( null === $tblTempInvoice ? null : $tblTempInvoice->getId() );
    }

    /**
     * @return bool|TblCommodity
     */
    public function getServiceBillingCommodity()
    {
        if (null === $this->serviceBilling_Commodity) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityCommodityById($this->serviceBilling_Commodity);
        }
    }

    /**
     * @param TblCommodity $tblCommodity
     */
    public function setServiceBillingCommodity( TblCommodity $tblCommodity = null )
    {
        $this->serviceBilling_Commodity = ( null === $tblCommodity ? null : $tblCommodity->getId() );
    }
}
