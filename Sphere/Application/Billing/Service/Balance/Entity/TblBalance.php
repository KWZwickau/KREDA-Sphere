<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance\Entity;

use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
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
    const ATTR_SERVICE_BILLING_INVOICE = 'serviceBilling_Invoice';

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
     * @param TblDebtor $serviceBilling_Banking
     */
    public function setServiceBillingBanking( TblDebtor $serviceBilling_Banking = null)
    {
        $this->serviceBilling_Banking = ( null === $serviceBilling_Banking ? null : $serviceBilling_Banking->getId() );
    }

    /**
     * @return integer
     */
    public function getServiceBillingBanking()
    {
            return $this->serviceBilling_Banking;
    }

    /**
     * @param null|TblInvoice $serviceBilling_Invoice
     */
    public function setServiceBillingInvoice( TblInvoice $serviceBilling_Invoice = null)
    {
        $this->serviceBilling_Invoice = ( null === $serviceBilling_Invoice ? null : $serviceBilling_Invoice->getId() );
    }

    /**
     * @return integer
     */
    public function getServiceBillingInvoice()
    {

            return $this->serviceBilling_Invoice;
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