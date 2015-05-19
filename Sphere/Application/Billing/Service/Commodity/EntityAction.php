<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;

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
}