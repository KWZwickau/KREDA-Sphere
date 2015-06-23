<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblPayment;
use KREDA\Sphere\Application\Billing\Service\Balance\EntityAction;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Balance extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Balance', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param int $Id
     *
     * @return bool|TblBalance
     */
    public function entityBalanceById( $Id )
    {
        return parent::entityBalanceById( $Id );
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool|TblBalance
     */
    public function entityBalanceByInvoice(TblInvoice $tblInvoice)
    {
        return parent::entityBalanceByInvoice($tblInvoice);
    }

    /**
     * @return bool|TblBalance[]
     */
    public function entityBalanceAll()
    {
        return parent::entityBalanceAll();
    }

    /**
     * @param int $Id
     *
     * @return bool|TblPayment
     */
    public function entityPaymentById( $Id )
    {
        return parent::entityPaymentById( $Id );
    }

    /**
     * @param TblBalance $Balance
     *
     * @return bool|TblPayment[]
     */
    public function entityPaymentByBalance( TblBalance $Balance )
    {
        return parent::entityPaymentByBalance( $Balance );
    }

    /**
     * @return bool|TblPayment[]
     */
    public function entityPaymentAll()
    {
        return parent::entityPaymentAll();
    }

    /**
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public function entityInvoiceHasFullPaymentAll()
    {
        return parent::entityInvoiceHasFullPaymentAll();
    }

    /**
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public function entityInvoiceHasExportDateAll()
    {
        return parent::entityInvoiceHasExportDateAll();
    }

    /**
     * @param TblBalance $tblBalance
     *
     * @return string
     */
    public function sumPriceItemStringByBalance( TblBalance $tblBalance )
    {
        return parent::sumPriceItemStringByBalance( $tblBalance );
    }

    /**
     * @param TblBalance $tblBalance
     *
     * @return float
     */
    public function sumPriceItemByBalance(TblBalance $tblBalance)
    {
        return parent::sumPriceItemByBalance($tblBalance);
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool
     */
    public function checkPaymentFromDebtorExistsByDebtor(TblDebtor $tblDebtor)
    {
        return parent::checkPaymentFromDebtorExistsByDebtor($tblDebtor);
    }

    /**
     * @param TblDebtor $serviceBilling_Banking
     * @param TblInvoice $serviceBilling_Invoice
     * @param $ExportDate
     *
     * @return bool
     */
    public function actionCreateBalance(TblDebtor $serviceBilling_Banking, TblInvoice $serviceBilling_Invoice, $ExportDate)
    {
        return parent::actionCreateBalance($serviceBilling_Banking, $serviceBilling_Invoice, $ExportDate);
    }

    /**
     * @param TblBalance $tblBalance
     * @param $Value
     * @param \DateTime $Date
     *
     * @return TblPayment|null
     */
    public function actionCreatePayment(TblBalance $tblBalance, $Value,\DateTime $Date)
    {
        return parent::actionCreatePayment($tblBalance, $Value, $Date);
    }
}
