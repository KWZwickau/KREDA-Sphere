<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
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
     * @return bool|TblInvoice[]
     */
    protected function entityInvoiceAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblInvoice' )->findAll();
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

    /**
     * @param            $IsPaid
     * @param            $Number
     * @param            $IsVoid
     * @param            $InvoiceDate
     * @param            $PaymentDate
     * @param            $Discount
     * @param            $PersonFirstName
     * @param            $PersonLastName
     * @param            $PersonSalutation
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
        $Entity->setPersonSalutation( $PersonSalutation );
        $Entity->setServiceManagementAddress( $tblAddress );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param            $CommodityName
     * @param            $CommodityDescription
     * @param            $ItemName
     * @param            $ItemDescription
     * @param            $ItemPrice
     * @param            $ItemQuantity
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

    /**
     * @param TblBasket $tblBasket
     * @param \DateTime $Date
     *
     * @return bool
     */
    protected function actionCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date
    )
    {

        $tblPersonAllByBasket = Billing::serviceBasket()->entityPersonAllByBasket( $tblBasket );
        $tblBasketItemAllByBasket = Billing::serviceBasket()->entityBasketItemAllByBasket( $tblBasket );

        $Manager = $this->getEntityManager();

        // TODO Debtor select
        // TODO InvoiceNumber create
        // TODO tblAddress

        foreach ($tblPersonAllByBasket as $tblPerson) {
            $Entity = new TblInvoice();
            $Entity->setIsPaid( false );
            $Entity->setIsVoid( false );
            $Entity->setNumber( "23934093243920" );
            //$Entity->setPaymentDate( $Date->sub(new \DateInterval('P'. Billing::serviceAccount()->entityDebtorByPerson($tblPerson)->getLeadTimeFollow() .'D') ));
            $Entity->setPaymentDate( ( new \DateTime( $Date ) )->sub( new \DateInterval( 'P10D' ) ) );// ->sub(new \DateInterval('P10D') ));
            $Entity->setInvoiceDate( new \DateTime( $Date ) );
            $Entity->setDiscount( 0 );
            $Entity->setPersonFirstName( $tblPerson->getFirstName() );
            $Entity->setPersonLastName( $tblPerson->getLastName() );
            $Entity->setPersonSalutation( $tblPerson->getTblPersonSalutation()->getName() );
            $Entity->setServiceManagementAddress( Management::serviceAddress()->entityAddressById( 1 ) );

            $Manager->SaveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );

            foreach ($tblBasketItemAllByBasket as $tblBasketItem) {
                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();

                $EntityItem = new TblInvoiceItem();
                $EntityItem->setCommodityName( $tblCommodity->getName() );
                $EntityItem->setCommodityDescription( $tblCommodity->getDescription() );
                $EntityItem->setItemName( $tblItem->getName() );
                $EntityItem->setItemDescription( $tblItem->getDescription() );
                if ($tblCommodity->getTblCommodityType()->getName() == 'Einzelleistung') {
                    $EntityItem->setItemPrice( $tblBasketItem->getPrice() );
                } else {
                    $EntityItem->setItemPrice( $tblBasketItem->getPrice() / Billing::serviceBasket()->countPersonByBasket( $tblBasket ) );
                }
                $EntityItem->setItemQuantity( $tblBasketItem->getQuantity() );
                $EntityItem->setTblInvoice( $Entity );

                $Manager->bulkSaveEntity( $EntityItem );
                System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $EntityItem );

//                $tblItemAccountList = Billing::serviceCommodity()->entityItemAccountAllByItem( $tblItem );
//                /** @var TblItemAccount $tblItemAccount */
//                foreach ($tblItemAccountList as $tblItemAccount)
//                {
//                    $EntityItemAccount = new TblInvoiceAccount();
//                    $EntityItemAccount->setTblInvoiceItem( $EntityItem );
//                    $EntityItemAccount->setServiceBilling_Account( $tblItemAccount->getServiceBilling_Account() );
//
//                    $Manager->bulkSaveEntity($EntityItemAccount);
//                    System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
//                        $EntityItemAccount );
//                }
            }
        }
        $Manager->flushCache();

        return true;
    }
}
