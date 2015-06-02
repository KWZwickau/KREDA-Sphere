<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblBalance")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblBalance extends AbstractEntity
{
    const ATTR_SERVICE_BILLING_BANKING = 'serviceBilling_Banking';
    const ATTR_SERVICE_BILLING_COMMODITY_ITEM = 'serviceBilling_Invoice';

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Banking;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Invoice;

    /**
     * @Column(type="date")
     */
    protected $ExportDate;

    /**
     * @param null|TblBanking $serviceBilling_Banking
     */
    public function setServiceBillingBanking($serviceBilling_Banking = null)
    {
        $this->serviceBilling_Banking = ( null === $serviceBilling_Banking ? null : $serviceBilling_Banking->getId() );
    }

    /**
     * @return bool|TblBanking
     */
    public function getServiceBillingBilling()
    {
        if (null === $this->serviceBilling_Banking) {
            return false;
        } else {
            return Billing::serviceBanking()->entityBankingById( $this->serviceBilling_Banking );
        }
    }

    /**
     * @param null|TblInvoice $serviceBilling_Invoice
     */
    public function setServiceBillingInvoice($serviceBilling_Invoice = null)
    {
        $this->serviceBilling_Invoice = ( null === $serviceBilling_Invoice ? null : $serviceBilling_Invoice->getId() );
    }

    /**
     * @return bool|TblInvoice
     */
    public function getServiceBillingInvoice()
    {
        if (null === $this->serviceBilling_Invoice) {
            return false;
        } else {
            return Billing::serviceInvoice()->entityInvoiceById( $this->serviceBilling_Invoice );
        }
    }

    /**
     * @return string
     */
    public function getExportDate()
    {

        if (null === $this->ExportDate) {
            return false;
        }
        /** @var \DateTime $ExportDate */
        $ExportDate = $this->ExportDate;
        if ($ExportDate instanceof \DateTime) {
            return $ExportDate->format( 'd.m.Y' );
        } else {
            return (string)$ExportDate;
        }
    }

    /**
     * @param \DateTime $ExportDate
     */
    public function setExportDate(\DateTime $ExportDate )
    {
        $this->ExportDate = $ExportDate;
    }
}