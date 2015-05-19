<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
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
}