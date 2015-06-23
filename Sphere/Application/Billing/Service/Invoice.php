<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblTempInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\EntityAction;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
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
     * @param $Number
     * @return bool|Invoice\Entity\TblInvoice
     */
    public function entityInvoiceByNumber($Number)
    {
        return parent::entityInvoiceByNumber($Number);
    }

    /**
     * @return bool|TblInvoice[]
     */
    public  function entityInvoiceAll()
    {
        return parent::entityInvoiceAll();
    }

    /**
     * @param $isConfirmed
     *
     * @return TblInvoice[]|bool
     */
    public  function entityInvoiceAllByIsConfirmedState( $isConfirmed )
    {
        $invoiceAllByConfirmed = array();
        $invoiceAllByNotConfirmed = array();
        $tblInvoiceAll = $this->entityInvoiceAll();

        if ($tblInvoiceAll)
        {
            foreach($tblInvoiceAll as $tblInvoice)
            {
                if ($tblInvoice->getIsConfirmed())
                {
                    $invoiceAllByConfirmed[] = $tblInvoice;
                }
                else
                {
                    $invoiceAllByNotConfirmed[] = $tblInvoice;
                }
            }
        }

        if ( $isConfirmed )
        {
            if (!empty($invoiceAllByConfirmed))
            {
                return $invoiceAllByConfirmed;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if (!empty($invoiceAllByNotConfirmed))
            {
                return $invoiceAllByNotConfirmed;
            }
            else
            {
                return false;
            }
        }
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
     *
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public  function entityInvoiceAllByIsVoidState($IsVoid)
    {
        return parent::entityInvoiceAllByIsVoidState($IsVoid);
    }

    /**
     * @param int $Id
     *
     * @return bool|TblInvoiceItem
     */
    public  function entityInvoiceItemById($Id)
    {
        return parent::entityInvoiceItemById($Id);
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool|Invoice\Entity\TblInvoiceItem[]
     */
    public  function entityInvoiceItemAllByInvoice(TblInvoice $tblInvoice)
    {
        return parent::entityInvoiceItemAllByInvoice($tblInvoice);
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|Invoice\Entity\TblTempInvoice[]
     */
    public function entityTempInvoiceAllByBasket(TblBasket $tblBasket)
    {
        return parent::entityTempInvoiceAllByBasket($tblBasket);
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return string
     */
    public  function sumPriceItemAllStringByInvoice(TblInvoice $tblInvoice)
    {
        return parent::sumPriceItemAllStringByInvoice($tblInvoice);
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return float
     */
    public function sumPriceItemAllByInvoice(TblInvoice $tblInvoice)
    {
        return parent::sumPriceItemAllByInvoice($tblInvoice);
    }


    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool
     */
    public function checkInvoiceFromDebtorIsPaidByDebtor(TblDebtor $tblDebtor)
    {
        return parent::checkInvoiceFromDebtorIsPaidByDebtor($tblDebtor);
    }

    /**
     * @param TblBasket $tblBasket
     * @param $Date
     *
     * @return bool
     */
    public function executeCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date
    )
    {
        return $this->actionCreateInvoiceListFromBasket( $tblBasket, $Date );
    }

    /**
     * @param TblInvoice $tblInvoice
     * @param $Data
     *
     * @return Success|string
     */
    public function executeConfirmInvoice(
        TblInvoice $tblInvoice,
        $Data
    )
    {
        if (Billing::serviceBalance()->actionCreateBalance(
            Billing::serviceBanking()->entityDebtorByDebtorNumber( $tblInvoice->getDebtorNumber()),
            $tblInvoice,
            null
        ))
        {
            return new Success( 'Die Rechnung wurde erfolgreich bestätigt und freigegeben' )
                .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed', 0 );
        }
        else
        {
            return new Warning( 'Die Rechnung wurde konnte nicht bestätigt und freigegeben werden' )
                .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2, array( 'Id' => $tblInvoice->getId()) );
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return string
     */
    public function executeCancelInvoice(
        TblInvoice $tblInvoice
    )
    {
        if (!$tblInvoice->getIsConfirmed())
        {
            if ($this->actionCancelInvoice($tblInvoice))
            {
                return new Success( 'Die Rechnung wurde erfolgreich storniert' )
                    .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed', 0 );
            }
            else
            {
                return new Warning( 'Die Rechnung konnte nicht storniert werden' )
                    .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2, array('Id'=>$tblInvoice->getId()) );
            }
        }
        else
        {
            //TODO cancel confirmed invoice
            if ($this->actionCancelInvoice($tblInvoice))
            {
                return new Success( 'Die Rechnung wurde erfolgreich storniert' )
                .new Redirect( '/Sphere/Billing/Invoice', 0 );
            }
            else
            {
                return new Warning( 'Die Rechnung konnte nicht storniert werden' )
                .new Redirect( '/Sphere/Billing/Invoice', 2 );
            }
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return string
     */
    public function executePayInvoice(
        TblInvoice $tblInvoice
    )
    {
        if ($this->actionPayInvoice($tblInvoice))
        {
            return new Success( 'Die Rechnung wurde erfolgreich bezahlt' )
            .new Redirect( '/Sphere/Billing/Balance', 0 );
        }
        else
        {
            return new Warning( 'Die Rechnung konnte nicht bezahlt werden' )
            .new Redirect( '/Sphere/Billing/Balance', 2 );
        }
    }

    /**
     * @param AbstractType   $View
     * @param TblInvoiceItem $tblInvoiceItem
     * @param                $InvoiceItem
     *
     * @return AbstractType|string
     */
    public function executeEditInvoiceItem(
        AbstractType &$View = null,
        TblInvoiceItem $tblInvoiceItem,
        $InvoiceItem
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $InvoiceItem
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $InvoiceItem['Price'] ) && empty( $InvoiceItem['Price'] )) {
            $View->setError( 'InvoiceItem[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
        if (isset( $InvoiceItem['Quantity'] ) && empty( $InvoiceItem['Quantity'] )) {
            $View->setError( 'InvoiceItem[Quantity]', 'Bitte geben Sie eine Menge an' );
            $Error = true;
        }

        if (!$Error) {
            if ($this->actionEditInvoiceItem(
                $tblInvoiceItem,
                $InvoiceItem['Price'],
                $InvoiceItem['Quantity']
            )
            ) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 1,
                        array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId() ) );
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' )
                    .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2,
                        array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId() ) );
            };
        }
        return $View;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     *
     * @return string
     */
    public function executeRemoveInvoiceItem(
        TblInvoiceItem $tblInvoiceItem
    )
    {
        if ($this->actionRemoveInvoiceItem( $tblInvoiceItem )) {
            return new Success( 'Der Artikel '.$tblInvoiceItem->getItemName().' wurde erfolgreich entfernt' )
            .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 0,
                array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId() ) );
        } else {
            return new Warning( 'Der Artikel '.$tblInvoiceItem->getItemName().' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2,
                array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId() ) );
        }
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblPerson $tblPerson
     * @param TblDebtor $tblDebtor
     *
     * @return Invoice\Entity\TblTempInvoice|null
     */
    public function executeCreateTempInvoice(
        TblBasket $tblBasket,
        TblPerson $tblPerson,
        TblDebtor $tblDebtor
    )
    {
        return $this->actionCreateTempInvoice( $tblBasket, $tblPerson, $tblDebtor );
    }

    /**
     * @param TblTempInvoice $tblTempInvoice
     * @param TblCommodity $tblCommodity
     *
     * @return Invoice\Entity\TblTempInvoiceCommodity|null
     */
    public function executeCreateTempInvoiceCommodity(
        TblTempInvoice $tblTempInvoice,
        TblCommodity $tblCommodity
    )
    {
        return $this->actionCreateTempInvoiceCommodity( $tblTempInvoice, $tblCommodity );
    }

    /**
     * @param TblInvoice $tblInvoice
     * @param TblAddress $tblAddress
     *
     * @return string
     */
    public function executeChangeInvoiceAddress(
        TblInvoice $tblInvoice,
        TblAddress $tblAddress
    )
    {
        if ($this->actionChangeInvoiceAddress( $tblInvoice, $tblAddress))
        {
            return new Success( 'Die Rechnungsadresse wurde erfolgreich geändert' )
                .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 0, array( 'Id' => $tblInvoice->getId() ) );
        }
        else
        {
            return new Warning( 'Die Rechnungsadresse konnte nicht geändert werden' )
                .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2, array( 'Id' => $tblInvoice->getId() ) );
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     * @param TblPaymentType $tblPaymentType
     *
     * @return string
     */
    public function executeChangeInvoicePaymentType(
        TblInvoice $tblInvoice,
        TblPaymentType $tblPaymentType
    )
    {
        if ($this->actionChangeInvoicePaymentType( $tblInvoice, $tblPaymentType))
        {
            return new Success( 'Die Zahlungsart wurde erfolgreich geändert' )
            .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 0, array( 'Id' => $tblInvoice->getId() ) );
        }
        else
        {
            return new Warning( 'Die Zahlungsart konnte nicht geändert werden' )
            .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', 2, array( 'Id' => $tblInvoice->getId() ) );
        }
    }
}
