<?php
namespace KREDA\Sphere\Application\Management\Service\Student;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblChildRank;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblStudent;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Student
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Name
     * @param string $Description
     *
     * @return TblChildRank
     */
    protected function actionCreateChildRank( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblChildRank' )
            ->findOneBy( array( TblChildRank::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblChildRank( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblChildRank
     */
    protected function entityChildRankById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblChildRank', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblStudent
     */
    protected function entityStudentByPerson( TblPerson $tblPerson )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblStudent' )->findBy( array(
            TblStudent::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ) );
        return ( empty( $Entity ) ? false : $Entity );
    }
}
