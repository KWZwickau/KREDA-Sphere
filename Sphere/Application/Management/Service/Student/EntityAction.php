<?php
namespace KREDA\Sphere\Application\Management\Service\Student;

use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
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
     * @param string       $StudentNumber
     * @param TblPerson    $tblPerson
     * @param TblCourse    $tblCourse
     * @param TblChildRank $tblChildRank
     *
     * @return TblStudent
     */
    protected function actionCreateStudent(
        $StudentNumber,
        TblPerson $tblPerson,
        TblCourse $tblCourse,
        TblChildRank $tblChildRank
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblStudent' )
            ->findOneBy( array(
                TblStudent::ATTR_STUDENT_NUMBER            => $StudentNumber,
                TblStudent::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblStudent( $StudentNumber );
            $Entity->setServiceManagementPerson( $tblPerson );
            $Entity->setServiceManagementCourse( $tblCourse );
            $Entity->setTblChildRank( $tblChildRank );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

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
     * @param string $Name
     *
     * @return bool|TblChildRank
     */
    protected function entityChildRankByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblChildRank' )->findOneBy( array(
            TblChildRank::ATTR_NAME => $Name
        ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblChildRank[]
     */
    protected function entityChildRankAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblChildRank' )->findAll();
        return ( empty( $Entity ) ? false : $Entity );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblStudent
     */
    protected function entityStudentByPerson( TblPerson $tblPerson )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblStudent' )->findOneBy( array(
            TblStudent::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ) );
        return ( empty( $Entity ) ? false : $Entity );
    }

    /**
     * @param string $StudentNumber
     *
     * @return bool|TblStudent
     */
    protected function entityStudentByNumber( $StudentNumber )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblStudent' )->findOneBy( array(
            TblStudent::ATTR_STUDENT_NUMBER => $StudentNumber
        ) );
        return ( empty( $Entity ) ? false : $Entity );
    }

    /**
     * @param TblStudent $tblStudent
     * @param string     $Date
     *
     * @return bool
     */
    protected function actionChangeTransferFromDate(
        TblStudent $tblStudent,
        $Date
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblStudent $Entity */
        $Entity = $Manager->getEntityById( 'TblStudent', $tblStudent->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            if (null === $Date) {
                $Entity->setTransferFromDate( null );
            } else {
                $Entity->setTransferFromDate( new \DateTime( $Date ) );
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
     * @param TblStudent $tblStudent
     * @param string     $Date
     *
     * @return bool
     */
    protected function actionChangeTransferToDate(
        TblStudent $tblStudent,
        $Date
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblStudent $Entity */
        $Entity = $Manager->getEntityById( 'TblStudent', $tblStudent->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            if (null === $Date) {
                $Entity->setTransferToDate( null );
            } else {
                $Entity->setTransferToDate( new \DateTime( $Date ) );
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
     * @param TblStudent $tblStudent
     * @param TblPerson  $tblPerson
     *
     * @return bool
     */
    protected function actionChangePerson(
        TblStudent $tblStudent,
        TblPerson $tblPerson
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblStudent $Entity */
        $Entity = $Manager->getEntityById( 'TblStudent', $tblStudent->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setServiceManagementPerson( $tblPerson );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }
}
