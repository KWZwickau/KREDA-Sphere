<?php
namespace KREDA\Sphere\Application\Billing\Service\Basket;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketPerson;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\TblAddress;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Basket
 */
abstract class EntityAction extends EntitySchema
{

    /***
     * @param $Id
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket
     */
    protected function entityBasketById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblBasket', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket[]
     */
    protected function entityBasketAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblBasket' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblCommodity[]
     */
    protected function entityCommodityAllByBasket( TblBasket $tblBasket )
    {

        $tblBasketItemAllByBasket = $this->entityBasketItemAllByBasket( $tblBasket );
        $EntityList = array();
        foreach ($tblBasketItemAllByBasket as $tblBasketItem) {
            $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
            if (!array_key_exists( $tblCommodity->getId(), $EntityList )) {
                $EntityList[$tblCommodity->getId()] = $tblCommodity;
            }
        }
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param \KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket $tblBasket
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem[]
     */
    protected function entityBasketItemAllByBasket( TblBasket $tblBasket )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblBasketItem' )
            ->findBy( array( TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblCommodity $tblCommodity
     *
     * @return TblBasketItem[]|bool
     */
    protected function entityBasketItemAllByBasketAndCommodity( TblBasket $tblBasket, TblCommodity $tblCommodity )
    {
        $tblBasketItemAllByBasket = $this->entityBasketItemAllByBasket( $tblBasket );
        $EntityList = array();
        foreach ($tblBasketItemAllByBasket as $tblBasketItem)
        {
            if ($tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity()->getId() == $tblCommodity->getId())
            {
                $EntityList[$tblBasketItem->getId()] = $tblBasketItem;
            }
        }
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $Id
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem
     */
    protected function entityBasketItemById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblBasketItem', $Id );
        return ( null === $Entity ? false : $Entity );
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
     * @param TblBasket $tblBasket
     * @return int
     */
    protected function countPersonByBasket( TblBasket $tblBasket)
    {
        return (int)$this->getEntityManager()->getEntity( 'TblBasketPerson' )->countBy( array(
            TblBasketPerson::ATTR_TBL_Basket => $tblBasket ->getId()
        ) );
    }

    /**
     * @param TblBasket $tblBasket
     * @param $Date
     * @param $TempTblInvoiceList
     * @param $SelectList
     *
     * @return bool
     */
    protected function checkDebtorsByBasket(
        TblBasket $tblBasket,
        $Date,
        &$TempTblInvoiceList,
        &$SelectList)
    {
        $tblCommodityAllByBasket = Billing::serviceBasket()->entityCommodityAllByBasket( $tblBasket );
        $tblBasketPersonAllByBasket = Billing::serviceBasket()->entityBasketPersonAllByBasket( $tblBasket );

        foreach($tblBasketPersonAllByBasket as $tblBasketPerson)
        {
            $tblPerson = Management::servicePerson()->entityPersonById( $tblBasketPerson->getServiceManagementPerson());
            foreach($tblCommodityAllByBasket as $tblCommodity)
            {
                /** @var TblDebtorCommodity[] $tblDebtorCommodityListByPersonAndCommodity */
                $tblDebtorCommodityListByPersonAndCommodity = array();
                /** @var TblDebtor[] $tblDebtorListByPerson */
                $tblDebtorListByPerson = array();

                $tblPersonRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson($tblPerson);
                foreach($tblPersonRelationshipList as $tblPersonRelationship)
                {
                    $tblDebtorList = Billing::serviceBanking()->entityDebtorAllByPerson(
                        Management::servicePerson()->entityPersonById($tblPersonRelationship->getTblPersonA()));
                    foreach($tblDebtorList as $tblDebtor)
                    {
                        $tblDebtorCommodityList = Billing::serviceBanking()->entityDebtorCommodityAllByDebtorAndCommodity( $tblDebtor, $tblCommodity );
                        foreach ($tblDebtorCommodityList as $tblDebtorCommodity)
                        {
                            $tblDebtorCommodityListByPersonAndCommodity[] = $tblDebtorCommodity;
                        }
                        $tblDebtorListByPerson[]=$tblDebtor;
                    }
                }

                if (empty($tblDebtorCommodityListByPersonAndCommodity))
                {
                    foreach($tblDebtorListByPerson as $tblDebtor)
                    {
                        $index = $this->searchArray($SelectList, "tblPerson", $tblPerson->getId(), "tblCommodity", $tblCommodity->getId());
                        if ($index === false) {
                            $SelectList[] = array(
                                'tblPerson' => $tblPerson->getId(),
                                'tblCommodity' => $tblCommodity->getId(),
                                'Debtors' => array($tblDebtor->getId())
                            );
                        }
                        else
                        {
                            $SelectList[$index]['Debtors'][]= $tblDebtor->getId();
                        }
                    }
                }
                else if (count($tblDebtorCommodityListByPersonAndCommodity) == 1)
                {
                    $index = $this->searchArray($TempTblInvoiceList, "tblPerson", $tblPerson->getId(),
                        "tblDebtor", $tblDebtorCommodityListByPersonAndCommodity[0]->getTblDebtor()->getId());
                    if ($index === false) {
                        $TempTblInvoiceList[] = array(
                            'tblPerson' => $tblPerson->getId(),
                            'tblDebtor' => $tblDebtorCommodityListByPersonAndCommodity[0]->getTblDebtor()->getId(),
                            'Commodities' => array($tblCommodity->getId())
                        );
                    }
                    else
                    {
                        $TempTblInvoiceList[$index]['Commodities'][]= $tblCommodity->getId();
                    }
                }
                else
                {
                    foreach ($tblDebtorCommodityListByPersonAndCommodity as $tblDebtorCommodityByPersonAndCommodity)
                    {
                        $index = $this->searchArray($SelectList, "tblPerson", $tblPerson->getId(), "tblCommodity", $tblCommodity->getId());
                        if ($index === false) {
                            $SelectList[] = array(
                                'tblPerson' => $tblPerson->getId(),
                                'tblCommodity' => $tblCommodity->getId(),
                                'Debtors' => array($tblDebtorCommodityByPersonAndCommodity->getTblDebtor()->getId())
                            );
                        }
                        else
                        {
                            $SelectList[$index]['Debtors'][]= $tblDebtorCommodityByPersonAndCommodity->getTblDebtor()->getId();
                        }
                    }
                }
            }
        }

        return empty($SelectList);
    }

    /**
     * @param $Date
     * @param $TempTblInvoiceList
     * @param $SelectList
     * @param $Data
     *
     * @return bool
     */
    protected function checkDebtors(
        $Date,
        $Data,
        &$TempTblInvoiceList,
        &$SelectList
    )
    {
//        print_r($SelectList);print_r('<br>');
//        print_r($Data);print_r('<br>');
        foreach ($Data as $Key => $Value)
        {
            $index = $this->searchArray($TempTblInvoiceList, "tblPerson", $SelectList[$Key]['tblPerson'],
                "tblDebtor", $Value );
            if ($index === false) {
                $TempTblInvoiceList[] = array(
                    'tblPerson' => $SelectList[$Key]['tblPerson'],
                    'tblDebtor' => $Value,
                    'Commodities' => array($SelectList[$Key]['tblCommodity'])
                );
            }
            else
            {
                $TempTblInvoiceList[$index]['Commodities'][]= $SelectList[$Key]['tblCommodity'];
            }
            unset($SelectList[$Key]);
        }
        return empty($SelectList);
    }

    /**
     * @param array $Array
     * @param $Key1
     * @param $Value1
     * @param $Key2
     * @param $Value2
     *
     * @return bool|int
     */
    private function searchArray(array $Array, $Key1, $Value1, $Key2, $Value2)
    {
        foreach ($Array as $Key => $Value)
        {
            if ($Value[$Key1] == $Value1 && $Value[$Key2] == $Value2)
            {
                return $Key;
            }
        }

        return false;
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
     * @param TblCommodity $tblCommodity
     * @param TblBasket    $tblBasket
     *
     * @return TblBasket
     */
    protected function actionCreateBasketItemsByCommodity(
        TblBasket $tblBasket,
        TblCommodity $tblCommodity
    ) {

        $Manager = $this->getEntityManager();

        $tblCommodityItemList = Billing::serviceCommodity()->entityCommodityItemAllByCommodity( $tblCommodity );

        /** @var TblCommodityItem $tblCommodityItem */
        foreach ($tblCommodityItemList as $tblCommodityItem)
        {
            $Entity = $Manager->getEntity( 'tblBasketItem' )->findOneBy(array(
                    TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId(),
                    TblBasketItem::ATTR_SERVICE_BILLING_COMMODITY_ITEM => $tblCommodityItem->getId()
            ));
            if (null === $Entity)
            {
                $Entity = new TblBasketItem();
                $Entity->setPrice( $tblCommodityItem->getTblItem()->getPrice() );
                $Entity->setQuantity( $tblCommodityItem->getQuantity() );
                $Entity->setServiceBillingCommodityItem( $tblCommodityItem );
                $Entity->setTblBasket( $tblBasket );

                $Manager->bulkSaveEntity( $Entity );
                System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
            }
        }
        $Manager->flushCache();

        return $tblBasket;
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param TblBasket    $tblBasket
     *
     * @return TblBasket
     */
    protected function actionDestroyBasketItemsByCommodity(
        TblBasket $tblBasket,
        TblCommodity $tblCommodity
    ) {

        $Manager = $this->getEntityManager();

        $tblBasketItemAllByBasket = Billing::serviceBasket()->entityBasketItemAllByBasket( $tblBasket );

        /** @var TblBasketItem $tblBasketItem */
        foreach ($tblBasketItemAllByBasket as $tblBasketItem) {
            if ($tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity()->getId() == $tblCommodity->getId()) {
                $Entity = $Manager->getEntity( 'tblBasketItem' )->findOneBy( array( 'Id' => $tblBasketItem->getId() ) );
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
                $Manager->bulkKillEntity( $Entity );
            }
        }
        $Manager->flushCache();

        return $tblBasket;
    }

    /**
     * @param TblBasketItem $tblBasketItem
     *
     * @return bool
     */
    protected function actionRemoveBasketItem(
        TblBasketItem $tblBasketItem
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblBasketItem' )->findOneBy(
            array(
                'Id' => $tblBasketItem->getId()
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
     * @param TblBasketItem $tblBasketItem
     * @param               $Price
     * @param               $Quantity
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
            $Entity->setPrice( str_replace( ',', '.', $Price ) );
            $Entity->setQuantity( str_replace( ',', '.', $Quantity ) );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param \KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket $tblBasket
     * @param TblPerson                                                         $tblPerson
     *
     * @return TblBasketPerson
     */
    protected function actionAddBasketPerson(
        TblBasket $tblBasket,
        TblPerson $tblPerson
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'tblBasketPerson' )->findOneBy(array(
                TblBasketPerson::ATTR_TBL_Basket => $tblBasket->getId(),
                TblBasketPerson::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ));
        if (null === $Entity)
        {
            $Entity = new TblBasketPerson();
            $Entity->setTblBasket( $tblBasket );
            $Entity->setServiceManagementPerson( $tblPerson );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblBasketPerson $tblBasketPerson
     *
     * @return bool
     */
    protected function actionRemoveBasketPerson(
        TblBasketPerson $tblBasketPerson
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblBasketPerson' )->findOneBy(
            array(
                'Id' => $tblBasketPerson->getId()
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
     * @param \KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket $tblBasket
     *
     * @return bool
     */
    protected function actionDestroyBasket(
        TblBasket $tblBasket
    ) {

        if ($tblBasket !== null) {
            $Manager = $this->getEntityManager();

            $EntityList = $Manager->getEntity( 'tblBasketPerson' )->findBy( array( TblBasketPerson::ATTR_TBL_Basket => $tblBasket->getId() ) );
            foreach ($EntityList as $Entity) {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $EntityList = $Manager->getEntity( 'tblBasketItem' )->findBy( array( TblBasketItem::ATTR_TBL_Basket => $tblBasket->getId() ) );
            foreach ($EntityList as $Entity) {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $Entity = $Manager->getEntity( 'tblBasket' )->findOneBy( array( 'Id' => $tblBasket->getId() ) );
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->bulkKillEntity( $Entity );

            $Manager->flushCache();

            return true;
        }

        return false;
    }
}
