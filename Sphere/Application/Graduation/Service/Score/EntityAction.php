<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score;

use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreCondition;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreRule;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Score
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreRule
     */
    protected function entityScoreRuleById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblScoreRule', $Id );
        return ( null === $Entity ? false : $Entity );
    }


    /**
     * @param integer $Id
     *
     * @return bool|TblScoreCondition
     */
    protected function entityScoreConditionById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblScoreCondition', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     * @param string $Description
     *
     * @return TblScoreRule
     */
    protected function actionCreateScoreRule( $Name, $Description = '' )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreRule' )
            ->findOneBy( array( TblScoreRule::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblScoreRule( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }
}
