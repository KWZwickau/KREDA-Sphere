<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Action;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\System\System;

/**
 * Class Change
 *
 * @package KREDA\Sphere\Application\Management\Service\Person\Action
 */
abstract class Change extends Create
{

    /**
     * @param TblPerson           $tblPerson
     * @param string              $Title
     * @param string              $FirstName
     * @param string              $MiddleName
     * @param string              $LastName
     *
     * @param string              $Birthday
     * @param string              $Birthplace
     *
     * @param string              $Nationality
     *
     * @param TblPersonSalutation $tblPersonSalutation
     * @param TblPersonGender     $tblPersonGender
     * @param TblPersonType       $tblPersonType
     *
     * @return bool
     */
    protected function actionChangePerson(
        TblPerson $tblPerson,
        $Title,
        $FirstName,
        $MiddleName,
        $LastName,
        $Birthday,
        $Birthplace,
        $Nationality,
        $tblPersonSalutation,
        $tblPersonGender,
        $tblPersonType
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblPerson $Entity */
        $Entity = $Manager->getEntityById( 'TblPerson', $tblPerson->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setTblPersonSalutation( $tblPersonSalutation );
            $Entity->setTitle( $Title );
            $Entity->setFirstName( $FirstName );
            $Entity->setMiddleName( $MiddleName );
            $Entity->setLastName( $LastName );
            $Entity->setTblPersonGender( $tblPersonGender );
            $Entity->setBirthday( new \DateTime( $Birthday ) );
            $Entity->setBirthplace( $Birthplace );
            $Entity->setNationality( $Nationality );
            $Entity->setTblPersonType( $tblPersonType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblPerson                 $tblPersonA
     * @param TblPerson                 $tblPersonB
     * @param TblPersonRelationshipType $tblPersonRelationshipType
     *
     * @return TblPersonRelationshipList
     */
    protected function actionAddRelationship(
        TblPerson $tblPersonA,
        TblPerson $tblPersonB,
        TblPersonRelationshipType $tblPersonRelationshipType
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPersonRelationshipList' )
            ->findOneBy( array(
                TblPersonRelationshipList::ATTR_TBL_PERSON_A                 => $tblPersonA->getId(),
                TblPersonRelationshipList::ATTR_TBL_PERSON_B                 => $tblPersonB->getId(),
                TblPersonRelationshipList::ATTR_TBL_PERSON_RELATIONSHIP_TYPE => $tblPersonRelationshipType->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblPersonRelationshipList();
            $Entity->setTblPersonA( $tblPersonA );
            $Entity->setTblPersonB( $tblPersonB );
            $Entity->setTblPersonRelationshipType( $tblPersonRelationshipType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblPerson                 $tblPersonA
     * @param TblPerson                 $tblPersonB
     * @param TblPersonRelationshipType $tblPersonRelationshipType
     *
     * @return bool
     */
    protected function actionRemoveRelationship(
        TblPerson $tblPersonA,
        TblPerson $tblPersonB,
        TblPersonRelationshipType $tblPersonRelationshipType
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblPersonRelationshipList $Entity */
        $Entity = $Manager->getEntity( 'TblPersonRelationshipList' )
            ->findOneBy( array(
                TblPersonRelationshipList::ATTR_TBL_PERSON_A                 => $tblPersonA->getId(),
                TblPersonRelationshipList::ATTR_TBL_PERSON_B                 => $tblPersonB->getId(),
                TblPersonRelationshipList::ATTR_TBL_PERSON_RELATIONSHIP_TYPE => $tblPersonRelationshipType->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
