<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Action;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\System\System;

/**
 * Class Create
 *
 * @package KREDA\Sphere\Application\Management\Service\Person\Action
 */
abstract class Create extends Entity
{

    /**
     * @param string $Name
     *
     * @return TblPersonSalutation
     */
    protected function actionCreateSalutation( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPersonSalutation' )->findOneBy( array( TblPersonSalutation::ATTR_NAME => $Name, ) );
        if (null === $Entity) {
            $Entity = new TblPersonSalutation();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }


    /**
     * @param string $Name
     *
     * @return TblPersonGender
     */
    protected function actionCreateGender( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPersonGender' )->findOneBy( array( TblPersonGender::ATTR_NAME => $Name, ) );
        if (null === $Entity) {
            $Entity = new TblPersonGender();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblPersonType
     */
    protected function actionCreateType( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPersonType' )->findOneBy( array( TblPersonType::ATTR_NAME => $Name, ) );
        if (null === $Entity) {
            $Entity = new TblPersonType();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblPersonRelationshipType
     */
    protected function actionCreateRelationshipType( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPersonRelationshipType' )->findOneBy( array( TblPersonRelationshipType::ATTR_NAME => $Name, ) );
        if (null === $Entity) {
            $Entity = new TblPersonRelationshipType();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param                     $Title
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
     * @param null $Remark
     *
     * @param null $Denomination
     *
     * @return TblPerson
     */
    protected function actionCreatePerson(
        $Title,
        $FirstName,
        $MiddleName,
        $LastName,
        $Birthday,
        $Birthplace,
        $Nationality,
        $tblPersonSalutation,
        $tblPersonGender,
        $tblPersonType,
        $Remark = null,
        $Denomination = null
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblPerson();
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
        $Entity->setRemark( $Remark );
        $Entity->setDenomination( $Denomination );
        $Manager->saveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }
}
