<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Invoice
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblInvoice
     */
    protected function entityInvoiceById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblInvoice', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblInvoiceItem
     */
    protected function entityInvoiceItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblInvoiceItem', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /***
     * @param $Id
     *
     * @return bool|TblBasket
     */
    protected function entityBasketById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblBasket', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $IsPaid
     * @param $Number
     * @param $IsVoid
     * @param $InvoiceDate
     * @param $PaymentDate
     * @param $Discount
     * @param $PersonFirstName
     * @param $PersonLastName
     * @param $PersonSalutation
     * @param TblAddress $tblAddress
     *
     * @return TblInvoice
     */
    protected function actionCreateInvoice(
        $IsPaid,
        $Number,
        $IsVoid,
        $InvoiceDate,
        $PaymentDate,
        $Discount,
        $PersonFirstName,
        $PersonLastName,
        $PersonSalutation,
        TblAddress $tblAddress
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblInvoice();
        $Entity->setIsPaid( $IsPaid );
        $Entity->setNumber( $Number );
        $Entity->setIsVoid( $IsVoid );
        $Entity->setInvoiceDate( $InvoiceDate );
        $Entity->setPaymentDate( $PaymentDate );
        $Entity->setDiscount( $Discount );
        $Entity->setPersonFirstName( $PersonFirstName );
        $Entity->setPersonLastName( $PersonLastName );
        $Entity->setPersonSalutation( $PersonSalutation);
        $Entity->setServiceManagement_Address( $tblAddress );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param $CommodityName
     * @param $CommodityDescription
     * @param $ItemName
     * @param $ItemDescription
     * @param $ItemPrice
     * @param $ItemQuantity
     * @param TblInvoice $tblInvoice
     *
     * @return TblInvoiceItem
     */
    protected function actionCreateInvoiceItem(
        $CommodityName,
        $CommodityDescription,
        $ItemName,
        $ItemDescription,
        $ItemPrice,
        $ItemQuantity,
        TblInvoice $tblInvoice
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblInvoiceItem();
        $Entity->setCommodityName( $CommodityName );
        $Entity->setCommodityDescription( $CommodityDescription );
        $Entity->setItemName( $ItemName );
        $Entity->setItemDescription( $ItemDescription );
        $Entity->setItemPrice( $ItemPrice );
        $Entity->setItemQuantity( $ItemQuantity );
        $Entity->setTblInvoice( $tblInvoice );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }
}
