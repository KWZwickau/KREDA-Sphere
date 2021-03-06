<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblTempInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblTempInvoiceCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
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
     * @return bool|TblTempInvoice
     */
    protected function entityTempInvoiceById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblTempInvoice', $Id );
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
     * @param $IsPaid
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsPaidState( $IsPaid )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_PAID => $IsPaid ) );
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
     * @param $Number
     * @return TblInvoice|bool
     */
    protected function entityInvoiceByNumber( $Number )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findOneBy( array( TblInvoice::ATTR_NUMBER => $Number ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblTempInvoice $tblTempInvoice
     * @return TblTempInvoiceCommodity[]|bool
     */
    protected function entityTempInvoiceCommodityAllByTempInvoice( TblTempInvoice $tblTempInvoice )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblTempInvoiceCommodity' )
            ->findBy( array( TblTempInvoiceCommodity::ATTR_TBL_TEMP_INVOICE => $tblTempInvoice->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblInvoice $tblInvoice
     * @return string
     */
    protected function sumPriceItemAllStringByInvoice( TblInvoice $tblInvoice)
    {
        $result = sprintf("%01.2f", $this->sumPriceItemAllByInvoice( $tblInvoice));
        return str_replace('.', ',', $result)  . " €";
    }

    /**
     * @param TblInvoice $tblInvoice
     * @return float
     */
    protected function sumPriceItemAllByInvoice( TblInvoice $tblInvoice)
    {
        $sum = 0.00;
        $tblInvoiceItemByInvoice = $this->entityInvoiceItemAllByInvoice( $tblInvoice);
        /** @var TblInvoiceItem $tblInvoiceItem */
        foreach($tblInvoiceItemByInvoice as $tblInvoiceItem)
        {
            $sum += $tblInvoiceItem->getItemPrice() * $tblInvoiceItem->getItemQuantity();
        }

        return $sum;
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
     * @return TblTempInvoice[]|bool
     */
    protected function entityTempInvoiceAllByBasket( TblBasket $tblBasket )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblTempInvoice' )
            ->findBy( array( TblTempInvoice::ATTR_SERVICE_BILLING_BASKET => $tblBasket->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @return bool
     */
    protected function checkInvoiceFromDebtorIsPaidByDebtor( TblDebtor $tblDebtor )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblInvoice' )->findOneBy(array(
            TblInvoice::ATTR_IS_PAID => true,
            TblInvoice::ATTR_DEBTOR_NUMBER => $tblDebtor->getDebtorNumber()
        ));
        return ( null === $Entity ? false : true );
    }

    /**
     * @param TblBasket $tblBasket
     * @param $Date
     *
     * @return bool
     */
    protected function actionCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date
    )
    {
        $Manager = $this->getEntityManager();
        $tblTempInvoiceList = $this->entityTempInvoiceAllByBasket( $tblBasket );

        foreach ($tblTempInvoiceList as $tblTempInvoice)
        {
            $tblDebtor = $tblTempInvoice->getServiceBillingDebtor();
            $tblPersonDebtor = Management::servicePerson()->entityPersonById($tblDebtor->getServiceManagementPerson());
            $tblPerson = $tblTempInvoice->getServiceManagementPerson();
            $Entity = new TblInvoice();
            $Entity->setIsPaid( false );
            $Entity->setIsVoid( false );
            $Entity->setNumber( "40000000" );
            $Entity->setBasketName($tblBasket->getName());
            $Entity->setServiceBillingBankingPaymentType( $tblDebtor->getPaymentType() );

            $leadTimeByDebtor = Billing::serviceBanking()->entityLeadTimeByDebtor( $tblDebtor );
            $invoiceDate = ( new \DateTime( $Date ) )->sub( new \DateInterval( 'P' . $leadTimeByDebtor .'D' ) );
            $now = new \DateTime();
            if (($invoiceDate->format('y.m.d')) >= ($now->format('y.m.d')))
            {
                $Entity->setInvoiceDate( $invoiceDate );
                $Entity->setPaymentDate( new \DateTime( $Date ) );
                $Entity->setIsPaymentDateModified( false );
            }
            else
            {
                $Entity->setInvoiceDate( new \DateTime('now') );
                $Entity->setPaymentDate( $now->add( new \DateInterval( 'P' . $leadTimeByDebtor .'D' ) ));
                $Entity->setIsPaymentDateModified( true );
            }

            $Entity->setDiscount( 0 );
            $Entity->setDebtorFirstName( $tblPersonDebtor->getFirstName() );
            $Entity->setDebtorLastName( $tblPersonDebtor->getLastName() );
            $Entity->setDebtorSalutation( $tblPersonDebtor->getTblPersonSalutation()->getName() );
            $Entity->setDebtorNumber($tblDebtor->getDebtorNumber());
            $Entity->setServiceManagementPerson( $tblPerson );
            if (($address = Management::servicePerson()->entityAddressAllByPerson( $tblPersonDebtor)))
            {
                // TODO address type invoice
                $Entity->setServiceManagementAddress( $address[0] );
            }

            $Manager->SaveEntity( $Entity );

            $Entity->setNumber( (int)$Entity->getNumber() + $Entity->getId() );
            $Manager->SaveEntity( $Entity );

            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );

            $tblTempInvoiceCommodityList = $this->entityTempInvoiceCommodityAllByTempInvoice( $tblTempInvoice );
            foreach ($tblTempInvoiceCommodityList as $tblTempInvoiceCommodity)
            {
                $tblCommodity = $tblTempInvoiceCommodity->getServiceBillingCommodity();
                $tblBasketItemAllByBasketAndCommodity = Billing::serviceBasket()->entityBasketItemAllByBasketAndCommodity($tblBasket, $tblCommodity);
                foreach ($tblBasketItemAllByBasketAndCommodity as $tblBasketItem)
                {
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
     * @param TblInvoice $tblInvoice
     *
     * @return bool
     */
    protected function actionPayInvoice(
        TblInvoice $tblInvoice
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsPaid( true );
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

        $Entity = $Manager->getEntity( 'TblInvoiceItem' )->findOneBy(
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

    /**
     * @param TblBasket $tblBasket
     * @param TblPerson $tblPerson
     * @param TblDebtor $tblDebtor
     *
     * @return TblTempInvoice|null
     */
    protected function actionCreateTempInvoice(
        TblBasket $tblBasket,
        TblPerson $tblPerson,
        TblDebtor $tblDebtor
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblTempInvoice' )->findOneBy( array(
            TblTempInvoice::ATTR_SERVICE_BILLING_BASKET => $tblBasket->getId(),
            TblTempInvoice::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId(),
            TblTempInvoice::ATTR_SERVICE_BILLING_DEBTOR => $tblDebtor ->getId()
        ));
        if (null === $Entity)
        {
            $Entity = new TblTempInvoice();
            $Entity->setServiceBillingBasket( $tblBasket );
            $Entity->setServiceManagementPerson( $tblPerson );
            $Entity->setServiceBillingDebtor( $tblDebtor );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblTempInvoice $tblTempInvoice
     * @param TblCommodity $tblCommodity
     *
     * @return TblTempInvoiceCommodity|null
     */
    protected function actionCreateTempInvoiceCommodity(
        TblTempInvoice $tblTempInvoice,
        TblCommodity $tblCommodity
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblTempInvoiceCommodity' )->findOneBy( array(
            TblTempInvoiceCommodity::ATTR_TBL_TEMP_INVOICE => $tblTempInvoice->getId(),
            TblTempInvoiceCommodity::ATTR_SERVICE_BILLING_COMMODITY => $tblCommodity->getId()
        ));
        if (null === $Entity)
        {
            $Entity = new TblTempInvoiceCommodity();
            $Entity->setTblTempInvoice( $tblTempInvoice );
            $Entity->setServiceBillingCommodity( $tblCommodity );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblInvoice $tblInvoice
     * @param TblAddress $tblAddress
     *
     * @return bool
     */
    protected function actionChangeInvoiceAddress(
        TblInvoice $tblInvoice,
        TblAddress $tblAddress
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        if (null !== $Entity)
        {
            $Protocol = clone $Entity;
            $Entity->setServiceManagementAddress( $tblAddress );

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
     * @param TblPaymentType $tblPaymentType
     *
     * @return bool
     */
    protected function actionChangeInvoicePaymentType(
        TblInvoice $tblInvoice,
        TblPaymentType $tblPaymentType
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        if (null !== $Entity)
        {
            $Protocol = clone $Entity;
            $Entity->setServiceBillingBankingPaymentType( $tblPaymentType );

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
     *
     * @return bool
     */
    protected function actionDestroyTempInvoice(
        TblBasket $tblBasket
    ) {

        if ($tblBasket !== null) {
            $Manager = $this->getEntityManager();

            /** @var  TblTempInvoice[] $EntityList */
            $EntityList = $Manager->getEntity( 'TblTempInvoice' )->findBy( array(
                TblTempInvoice::ATTR_SERVICE_BILLING_BASKET => $tblBasket->getId()
            ) );
            foreach ($EntityList as $Entity) {
                $EntitySubList = $Manager->getEntity( 'TblTempInvoiceCommodity' )->findBy( array(
                    TblTempInvoiceCommodity::ATTR_TBL_TEMP_INVOICE => $Entity->getId()
                ) );
                foreach ($EntitySubList as $SubEntity) {
                    System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                        $SubEntity );
                    $Manager->bulkKillEntity( $SubEntity );
                }
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $Manager->flushCache();

            return true;
        }

        return false;
    }
}
