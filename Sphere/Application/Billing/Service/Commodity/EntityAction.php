<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityType;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
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

    protected function entityCommodityItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCommodityItem', $Id );
        return ( null === $Entity ? false : $Entity );
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
        foreach($tblCommodityItemByCommodity as $tblCommodityItem)
        {
            $sum += $tblCommodityItem->getTblItem()->getPrice() * $tblCommodityItem->getQuantity();
        }

        return $sum;
    }

    /**
     * @param string              $Name
     * @param string              $Description
     *
     * @return TblCommodity
     */
    protected function actionCreateCommodity(
        $Name,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblCommodity();
        $Entity->setName($Name);
        $Entity->setDescription( $Description );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param $Name
     * @param $Description
     *
     * @return bool
     */
    protected function actionEditCommodity(
        TblCommodity $tblCommodity,
        $Name,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        /** @var TblCommodity $Entity */
        $Entity = $Manager->getEntityById( 'TblCommodity', $tblCommodity->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );

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
     *
     * @return TblItem
     */
    protected function actionCreateItem(
        $Name,
        $Description,
        $Price,
        $CostUnit
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblItem();
        $Entity->setName( $Name );
        $Entity->setDescription( $Description );
        $Entity->setPrice( $Price );
        $Entity->setCostUnit( $CostUnit );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
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
        $Entity->setQuantity( $Quantity );

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