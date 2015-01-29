<?php
namespace KREDA\Sphere\Application\Graduation\Service\Grade;

use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Grade
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblGradeType
     */
    protected function entityGradeTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblGradeType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Acronym
     *
     * @return bool|TblGradeType
     */
    protected function entityGradeTypeByAcronym( $Acronym )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblGradeType' )->findOneBy( array(
            TblGradeType::ATTR_ACRONYM => $Acronym
        ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Acronym
     * @param string $Name
     *
     * @return TblGradeType
     */
    protected function actionCreateGradeType( $Acronym, $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblGradeType' )
            ->findOneBy( array( TblGradeType::ATTR_ACRONYM => $Acronym ) );
        if (null === $Entity) {
            $Entity = new TblGradeType( $Acronym );
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

}
