<?php
namespace KREDA\Sphere\Application\Graduation\Service\Weight;

use KREDA\Sphere\Application\Graduation\Service\Weight\Entity\TblWeightDimension;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Weight
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblWeightDimension
     */
    protected function entityWeightDimensionById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblWeightDimension', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblWeightDimension
     */
    protected function entityWeightDimensionByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblWeightDimension' )->findOneBy( array(
            TblWeightDimension::ATTR_NAME => $Name
        ) );
        return ( null === $Entity ? false : $Entity );
    }
}
