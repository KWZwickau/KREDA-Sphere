<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Balance\EntityAction;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
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
     * @param TblInvoice $tblInvoice
     *
     * @return bool|TblBalance
     */
    public function entityBalanceByInvoice(TblInvoice $tblInvoice)
    {
        return parent::entityBalanceByInvoice($tblInvoice);
    }

    /**
     * @return bool|Balance\Entity\TblBalance[]
     */
    public function entityBalanceAll()
    {
        return parent::entityBalanceAll();
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
