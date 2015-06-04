<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblPayment;
use KREDA\Sphere\Application\Billing\Service\Balance\EntityAction;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
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
     * @return bool|TblBalance[]
     */
    public function entityBalanceAll()
    {
        return parent::entityBalanceAll();
    }

    /**
     * @return bool|TblPayment[]
     */
    public function entityPaymentAll()
    {
        return parent::entityPaymentAll();
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
     * @param int $Id
     *
     * @return bool|TblBalance
     */
    public function entityBalanceById( $Id )
    {
        return parent::entityBalanceById( $Id );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

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
     * @param TblBalance $Balance
     *
     * @return bool|TblPayment[]
     */
    public function entityPaymentByBalance( TblBalance $Balance )
    {
        return parent::entityPaymentByBalance( $Balance );
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
}
