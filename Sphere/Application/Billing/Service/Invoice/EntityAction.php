<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketPerson;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
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
     * @param $Id
     * @return bool|TblBasketItem
     */
    protected function entityBasketItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblBasketItem', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblBasketItem[]
     */
    protected function entityBasketItemAllByBasket( TblBasket $tblBasket )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblBasketItem' )
            ->findBy( array( TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /***
     * @param $Id
     *
     * @return bool|TblBasketPerson
     */
    protected function entityBasketPersonById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblBasketPerson', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblBasketPerson[]
     */
    protected function entityBasketPersonAllByBasket( TblBasket $tblBasket )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblBasketPerson' )
            ->findBy( array( TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
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

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return TblBasket
     */
    protected function actionCreateBasketItemsByCommodity(
        TblCommodity $tblCommodity
    ) {

        $tblBasket = $this->actionCreateBasket();

        $Manager = $this->getEntityManager();

        $tblCommodityItemList = Billing::serviceCommodity()->entityCommodityItemAllByCommodity( $tblCommodity );

        /** @var TblCommodityItem $tblCommodityItem */
        foreach ($tblCommodityItemList as $tblCommodityItem) {
            $Entity = new TblBasketItem();
            $Entity->setPrice( $tblCommodityItem->getTblItem()->getPrice() );
            $Entity->setQuantity( $tblCommodityItem->getQuantity() );
            $Entity->setServiceBillingCommodityItem( $tblCommodityItem );
            $Entity->setTblBasket( $tblBasket );

            $Manager->bulkSaveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        $Manager->flushCache();

        return $tblBasket;
    }

    /**
     * @return TblBasket
     */
    protected function actionCreateBasket()
    {
        $Manager = $this->getEntityManager();

        $Entity = new TblBasket();

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblBasketItem $tblBasketItem
     *
     * @return bool
     */
    protected function actionRemoveBasketItem(
        TblBasketItem $tblBasketItem
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblBasketItem' )->findOneBy(
            array(
                'Id' => $tblBasketItem->getId()
            ) );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblBasketItem $tblBasketItem
     * @param $Price
     * @param $Quantity
     *
     * @return bool
     */
    protected function actionEditBasketItem(
        TblBasketItem $tblBasketItem,
        $Price,
        $Quantity
    ) {
        $Manager = $this->getEntityManager();

        /** @var TblBasketItem $Entity */
        $Entity = $Manager->getEntityById( 'TblBasketItem', $tblBasketItem->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setPrice( str_replace(',','.', $Price) );
            $Entity->setQuantity( str_replace(',','.', $Quantity) );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblPerson $tblPerson
     *
     * @return TblBasketPerson
     */
    protected function actionAddBasketPerson(
        TblBasket $tblBasket,
        TblPerson $tblPerson
    ) {
        $Manager = $this->getEntityManager();

        $Entity = new TblBasketPerson();
        $Entity->setTblBasket( $tblBasket );
        $Entity->setServiceManagementPerson( $tblPerson );

        $Manager->saveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblBasketPerson $tblBasketPerson
     *
     * @return bool
     */
    protected function actionRemoveBasketPerson(
        TblBasketPerson $tblBasketPerson
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblBasketPerson' )->findOneBy(
            array(
                'Id' => $tblBasketPerson->getId()
            ) );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool
     */
    protected function actionDestroyBasket(
        TblBasket $tblBasket
    )
    {
        if ($tblBasket !== null)
        {
            $Manager = $this->getEntityManager();

            $EntityList = $Manager->getEntity('tblBasketPerson')->findBy(array(TblBasketPerson::ATTR_TBL_Basket => $tblBasket->getId()) );
            foreach ($EntityList as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $EntityList = $Manager->getEntity('tblBasketItem')->findBy(array(TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId()) );
            foreach ($EntityList as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $Entity = $Manager->getEntity('tblBasket')->findOneBy(array('Id' => $tblBasket->getId()) );
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->bulkKillEntity( $Entity );

            $Manager->flushCache();

            return true;
        }

        return false;
    }
}
