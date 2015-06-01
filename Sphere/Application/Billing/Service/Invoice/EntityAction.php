<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Management;
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
     * @param $IsConfirmed
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsConfirmedState( $IsConfirmed )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_CONFIRMED => $IsConfirmed, TblInvoice::ATTR_IS_VOID => false ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $IsPaid
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsPaidState( $IsPaid )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_PAID => $IsPaid, TblInvoice::ATTR_IS_VOID => false ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $IsVoid
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsVoidState( $IsVoid )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_VOID => $IsVoid ) );
        return ( null === $EntityList ? false : $EntityList );
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
     * @param TblInvoice $tblInvoice
     *
     * @return TblInvoiceItem[]|bool
     */
    protected function entityInvoiceItemAllByInvoice( TblInvoice $tblInvoice )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoiceItem' )
            ->findBy( array( TblInvoiceItem::ATTR_TBL_INVOICE => $tblInvoice->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
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

        // TODO Debtor select, Vorlaufzeit, DebtorNumber
        // TODO InvoiceNumber create
        // TODO tblAddress

        foreach ($tblPersonAllByBasket as $tblPerson) {
            $Entity = new TblInvoice();
            $Entity->setIsConfirmed( false );
            $Entity->setIsPaid( false );
            $Entity->setIsVoid( false );
            $Entity->setNumber( "23934093243920" );
            //$Entity->setPaymentDate( $Date->sub(new \DateInterval('P'. Billing::serviceAccount()->entityDebtorByPerson($tblPerson)->getLeadTimeFollow() .'D') ));
            $Entity->setPaymentDate( ( new \DateTime( $Date ) )->sub( new \DateInterval( 'P5D' ) ) );
            $Entity->setInvoiceDate( new \DateTime( $Date ) );
            $Entity->setDiscount( 0 );
            $Entity->setDebtorFirstName( $tblPerson->getFirstName() );
            $Entity->setDebtorLastName( $tblPerson->getLastName() );
            $Entity->setDebtorSalutation( $tblPerson->getTblPersonSalutation()->getName() );
            $Entity->setDebtorNumber("1234245");
            $Entity->setServiceManagementPerson( $tblPerson );

            $Manager->SaveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );

            foreach ($tblBasketItemAllByBasket as $tblBasketItem) {
                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();

                if (!($tblItem->getServiceManagementCourse()) && !($tblItem->getServiceManagementStudentChildRank()))
                {
                    $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                }
                else if ($tblItem->getServiceManagementCourse() && !($tblItem->getServiceManagementStudentChildRank()))
                {
                    if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                        && $tblItem->getServiceManagementCourse()->getId() == $tblStudent->getServiceManagementCourse()->getId()
                    )
                    {
                        $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                    }
                }
                else if (!($tblItem->getServiceManagementCourse()) && $tblItem->getServiceManagementStudentChildRank())
                {
                    if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                        && $tblItem->getServiceManagementStudentChildRank()->getId() == $tblStudent->getTblChildRank()->getId())
                    {
                        $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                    }
                }
                else if ($tblItem->getServiceManagementCourse() && $tblItem->getServiceManagementStudentChildRank())
                {
                    if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                        && $tblItem->getServiceManagementCourse()->getId() == $tblStudent->getServiceManagementCourse()->getId()
                            && $tblItem->getServiceManagementStudentChildRank()->getId() == $tblStudent->getTblChildRank()->getId())
                    {
                        $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param TblItem $tblItem
     * @param TblBasket $tblBasket
     * @param TblBasketItem $tblBasketItem
     * @param TblInvoice $tblInvoice
     */
    private function actionCreateInvoiceItem(
        TblCommodity $tblCommodity,
        TblItem $tblItem,
        TblBasket $tblBasket,
        TblBasketItem $tblBasketItem,
        TblInvoice $tblInvoice
    )
    {
        $Entity = new TblInvoiceItem();
        $Entity->setCommodityName( $tblCommodity->getName() );
        $Entity->setCommodityDescription( $tblCommodity->getDescription() );
        $Entity->setItemName( $tblItem->getName() );
        $Entity->setItemDescription( $tblItem->getDescription() );
        if ($tblCommodity->getTblCommodityType()->getName() == 'Einzelleistung') {
            $Entity->setItemPrice( $tblBasketItem->getPrice() );
        } else {
            $Entity->setItemPrice( $tblBasketItem->getPrice() / Billing::serviceBasket()->countPersonByBasket( $tblBasket ) );
        }
        $Entity->setItemQuantity( $tblBasketItem->getQuantity() );
        $Entity->setTblInvoice( $tblInvoice );

        $this->getEntityManager()->SaveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
            $Entity );

        $tblItemAccountList = Billing::serviceCommodity()->entityItemAccountAllByItem( $tblItem );
        /** @var TblItemAccount $tblItemAccount */
        foreach ($tblItemAccountList as $tblItemAccount)
        {
            $EntityItemAccount = new TblInvoiceAccount();
            $EntityItemAccount->setTblInvoiceItem( $Entity );
            $EntityItemAccount->setServiceBilling_Account( $tblItemAccount->getServiceBilling_Account() );

            $this->getEntityManager()->SaveEntity($EntityItemAccount);
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $EntityItemAccount );
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool
     */
    protected function actionConfirmInvoice(
        TblInvoice $tblInvoice
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsConfirmed( true );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool
     */
    protected function actionCancelInvoice(
        TblInvoice $tblInvoice
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsVoid( true );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     * @param $Price
     * @param $Quantity
     *
     * @return bool
     */
    protected function actionEditInvoiceItem(
        TblInvoiceItem $tblInvoiceItem,
        $Price,
        $Quantity
    ) {

        $Manager = $this->getEntityManager();

        /** @var TblInvoiceItem $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoiceItem', $tblInvoiceItem->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setItemPrice( str_replace( ',', '.', $Price ) );
            $Entity->setItemQuantity( str_replace( ',', '.', $Quantity ) );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     *
     * @return bool
     */
    protected function actionRemoveInvoiceItem(
        TblInvoiceItem $tblInvoiceItem
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblInvoiceItem' )->findOneBy(
            array(
                'Id' => $tblInvoiceItem->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }


}
