<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\EntityAction;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Invoice extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Invoice', $this->getConsumerSuffix() );
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
     * @return bool|TblInvoice
     */
    public  function entityInvoiceById($Id)
    {
        return parent::entityInvoiceById($Id);
    }

    /**
     * @return bool|TblInvoice[]
     */
    public  function entityInvoiceAll()
    {
        return parent::entityInvoiceAll();
    }

    /**
     * @param $IsConfirmed
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public  function entityInvoiceAllByIsConfirmedState($IsConfirmed)
    {
        return parent::entityInvoiceAllByIsConfirmedState($IsConfirmed);
    }

    /**
     * @param $IsPaid
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public function entityInvoiceAllByIsPaidState($IsPaid)
    {
        return parent::entityInvoiceAllByIsPaidState($IsPaid);
    }

    /**
     * @param $IsVoid
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public  function entityInvoiceAllByIsVoidState($IsVoid)
    {
        return parent::entityInvoiceAllByIsVoidState($IsVoid);
    }

    /**
     * @param TblBasket $tblBasket
     * @param \DateTime $Date
     *
     * @return bool
     */
    public function executeCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date
    )
    {
        return $this->actionCreateInvoiceListFromBasket($tblBasket, $Date);
    }
}
