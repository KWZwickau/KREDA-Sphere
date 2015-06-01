<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityType;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Commodity
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblCommodity
     */
    protected function entityCommodityById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCommodity', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblCommodity[]
     */
    protected function entityCommodityAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblCommodity' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     *
     * @return bool|TblCommodityType
     */
    protected function entityCommodityTypeById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCommodityType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblCommodityType[]
     */
    protected function entityCommodityTypeAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblCommodityType' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblItem
     */
    protected function entityItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblItem', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblItem[]
     */
    protected function entityItemAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblItem' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param Entity\TblCommodity $tblCommodity
     *
     * @return bool|TblItem[]
     */
    protected function entityItemAllByCommodity( TblCommodity $tblCommodity )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblCommodityItem' )
            ->findBy( array( TblCommodityItem::ATTR_TBL_COMMODITY => $tblCommodity->getId() ) );
        if (!empty( $EntityList )) {
            array_walk( $EntityList, function ( TblCommodityItem &$tblCommodityItem ) {

                $tblCommodityItem = $tblCommodityItem->getTblItem();
            } );
        }
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param Entity\TblCommodity $tblCommodity
     *
     * @return bool|TblItem[]
     */
    protected function entityCommodityItemAllByCommodity( TblCommodity $tblCommodity )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblCommodityItem' )
            ->findBy( array( TblCommodityItem::ATTR_TBL_COMMODITY => $tblCommodity->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblItem $tblItem
     *
     * @return bool|TblItem[]
     */
    protected function entityCommodityItemAllByItem( TblItem $tblItem )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblCommodityItem' )
            ->findBy( array( TblCommodityItem::ATTR_TBL_ITEM => $tblItem->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $Id
     *
     * @return bool|TblCommodityItem
     */
    protected function entityCommodityItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCommodityItem', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     * @return bool|TblItemAccount
     */
    protected function entityItemAccountById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblItemAccount', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblItem $tblItem
     *
     * @return TblItemAccount[]|bool
     */
    protected function entityItemAccountAllByItem( TblItem $tblItem )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblItemAccount' )
            ->findBy( array( TblItemAccount::ATTR_TBL_Item => $tblItem->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblItem $tblItem
     * @return TblAccount[]
     */
    protected function entityAccountAllByItem(TblItem $tblItem)
    {
        $tblItemAccountAllByItem = $this->entityItemAccountAllByItem($tblItem);
        $tblAccount = array();
        foreach($tblItemAccountAllByItem as $tblItemAccount)
        {
            array_push($tblAccount, $tblItemAccount->getServiceBilling_Account());
        }

        return $tblAccount;
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return int
     */
    protected function countItemAllByCommodity( TblCommodity $tblCommodity )
    {
        return (int)$this->getEntityManager()->getEntity( 'TblCommodityItem' )->countBy( array(
            TblCommodityItem::ATTR_TBL_COMMODITY => $tblCommodity->getId()
        ) );
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return float (type="decimal", precision=14, scale=4)
     */
    protected function sumPriceItemAllByCommodity( TblCommodity $tblCommodity)
    {
        //(type="decimal", precision=14, scale=4)
        $sum = 0.00;
        $tblCommodityItemByCommodity = $this->entityCommodityItemAllByCommodity( $tblCommodity);
        /** @var TblCommodityItem $tblCommodityItem */
        foreach($tblCommodityItemByCommodity as $tblCommodityItem)
        {
            $sum += $tblCommodityItem->getTblItem()->getPrice() * $tblCommodityItem->getQuantity();
        }

        return $sum;
    }

    /**
     * @param $Name
     * @param $Description
     * @param TblCommodityType $tblCommodityType
     *
     * @return TblCommodity
     */
    protected function actionCreateCommodity(
        $Name,
        $Description,
        TblCommodityType $tblCommodityType
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblCommodity();
        $Entity->setName($Name);
        $Entity->setDescription( $Description );
        $Entity->setTblCommodityType( $tblCommodityType );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param $Name
     * @param $Description
     * @param Entity\TblCommodityType $tblCommodityType
     *
     * @return bool
     */
    protected function actionEditCommodity(
        TblCommodity $tblCommodity,
        $Name,
        $Description,
        TblCommodityType $tblCommodityType
    ) {

        $Manager = $this->getEntityManager();

        /** @var TblCommodity $Entity */
        $Entity = $Manager->getEntityById( 'TblCommodity', $tblCommodity->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Entity->setTblCommodityType( $tblCommodityType );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param $Name
     * @param $Description
     * @param $Price
     * @param $CostUnit
     * @param $Course
     * @param $ChildRank
     * @return TblItem
     */
    protected function actionCreateItem(
        $Name,
        $Description,
        $Price,
        $CostUnit,
        $Course,
        $ChildRank
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblItem();
        $Entity->setName( $Name );
        $Entity->setDescription( $Description );
        $Entity->setPrice( str_replace(',','.', $Price) );
        $Entity->setCostUnit( $CostUnit );
        if (Management::serviceCourse()->entityCourseById($Course))
        {
            $Entity->setServiceManagementCourse(Management::serviceCourse()->entityCourseById($Course));
        }
        if (Management::serviceStudent()->entityChildRankById($ChildRank))
        {
            $Entity->setServiceManagementStudentChildRank(Management::serviceStudent()->entityChildRankById($ChildRank));
        }
        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblItem $tblItem
     * @param $Name
     * @param $Description
     * @param $Price
     * @param $CostUnit
     * @param $Course
     * @param $ChildRank
     * @return bool
     */
    protected function actionEditItem(
        TblItem $tblItem,
        $Name,
        $Description,
        $Price,
        $CostUnit,
        $Course,
        $ChildRank
    ) {
        $Manager = $this->getEntityManager();

        /** @var TblItem $Entity */
        $Entity = $Manager->getEntityById( 'TblItem', $tblItem->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Entity->setPrice( str_replace(',','.', $Price) );
            $Entity->setCostUnit( $CostUnit );
            if (Management::serviceCourse()->entityCourseById($Course))
            {
                $Entity->setServiceManagementCourse(Management::serviceCourse()->entityCourseById($Course));
            }
            if (Management::serviceStudent()->entityChildRankById($ChildRank))
            {
                $Entity->setServiceManagementStudentChildRank(Management::serviceStudent()->entityChildRankById($ChildRank));
            }

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblItem $tblItem
     *
     * @return bool
     */
    protected function actionDestroyItem(
        TblItem $tblItem
    )
    {
        $Manager = $this->getEntityManager();

        $EntityList = $Manager->getEntity('tblCommodityItem')->findBy(array(TblCommodityItem::ATTR_TBL_ITEM => $tblItem->getId()));
        if (empty($EntityList))
        {
            $EntityItems = $Manager->getEntity( 'tblItemAccount' )
                ->findBy( array(TblItemAccount::ATTR_TBL_Item => $tblItem->getId() ) );
            if (null !== $EntityItems)
            {
                foreach($EntityItems as $Entity)
                {
                    System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                    $Manager->killEntity( $Entity );
                }
            }

            $Entity = $Manager->getEntity('tblItem')->findOneBy( array('Id'=>$tblItem->getId() ) );
            if (null !== $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
                return true;
            }
        }
        return false;
    }

    /**
     * @param TblItem $tblItem
     * @param TblAccount $tblAccount
     *
     * @return TblItemAccount|null
     */
    protected function actionAddItemAccount(
        TblItem $tblItem,
        TblAccount $tblAccount
    ) {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblItemAccount' )->findOneBy(
            array(
                TblItemAccount::ATTR_TBL_Item => $tblItem->getId(),
                TblItemAccount::ATTR_SERVICE_BILLING_ACCOUNT => $tblAccount->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblItemAccount();
            $Entity->setTblItem( $tblItem );
            $Entity->setTblAccount( $tblAccount );

            $Manager->saveEntity( $Entity );

            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblItemAccount $tblItemAccount
     *
     * @return bool
     */
    protected function actionRemoveItemAccount(
        TblItemAccount $tblItemAccount
    ) {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblItemAccount' )->findOneBy(
            array(
               'Id' => $tblItemAccount->getId()
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
     * @param Entity\TblCommodity $tblCommodity
     * @param Entity\TblItem $tblItem
     * @param $Quantity
     *
     * @return bool
     */
    protected function actionAddCommodityItem(
        TblCommodity $tblCommodity,
        TblItem $tblItem,
        $Quantity
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = new TblCommodityItem();
        $Entity->setTblCommodity( $tblCommodity );
        $Entity->setTblItem( $tblItem );
        $Entity->setQuantity( str_replace(',','.', $Quantity) );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblCommodityItem $tblCommodityItem
     *
     * @return bool
     */
    protected function actionRemoveCommodityItem(
        TblCommodityItem $tblCommodityItem
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'tblCommodityItem' )->findOneBy( array('Id'=>$tblCommodityItem->getId() ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param Entity\TblCommodity $tblCommodity
     *
     * @return bool
     */
    protected function actionRemoveCommodity(
        TblCommodity $tblCommodity
    )
    {
        $Manager = $this->getEntityManager();

        $EntityItems = $Manager->getEntity( 'tblCommodityItem' )
            ->findBy( array(TblCommodityItem::ATTR_TBL_COMMODITY => $tblCommodity->getId() ) );
        if (null !== $EntityItems)
        {
            foreach($EntityItems as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
            }
        }

        $Entity = $Manager->getEntity('tblCommodity')->findOneBy( array('Id'=>$tblCommodity->getId() ) );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param $Name
     *
     * @return TblCommodityType
     */
    protected function actionCreateCommodityType( $Name )
    {
        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblCommodityType' )->findOneBy( array( 'Name' => $Name, ) );
        if (null === $Entity)
        {
            $Entity = new TblCommodityType();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }
}