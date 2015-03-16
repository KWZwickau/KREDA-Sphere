<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use Doctrine\ORM\Query\Expr;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblPerson
     */
    protected function entityPersonById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPerson', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPerson[]
     */
    protected function entityPersonAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPerson' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return array|bool
     */
    protected function listPersonNationality()
    {

        $Query = $this->getEntityManager()->getEntity( 'TblPerson' )
            ->createQueryBuilder( 'p' )
            ->select( 'p.Nationality' )
            ->distinct( true )
            ->getQuery();
        $EntityList = $Query->getArrayResult();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return array|bool
     */
    protected function listPersonBirthplace()
    {

        $Query = $this->getEntityManager()->getEntity( 'TblPerson' )
            ->createQueryBuilder( 'p' )
            ->select( 'p.Birthplace' )
            ->distinct( true )
            ->getQuery();
        $EntityList = $Query->getArrayResult();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return int
     */
    protected function countPersonAll()
    {

        return (int)$this->getEntityManager()->getEntity( 'TblPerson' )->count();
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return int
     */
    protected function countPersonAllByType( TblPersonType $tblPersonType )
    {

        return (int)$this->getEntityManager()->getEntity( 'TblPerson' )->countBy( array(
            TblPerson::ATTR_TBL_PERSON_TYPE => $tblPersonType->getId()
        ) );
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return bool|Entity\TblPerson[]
     */
    protected function entityPersonAllByType(
        TblPersonType $tblPersonType
    ) {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPerson' )->findBy( array(
            TblPerson::ATTR_TBL_PERSON_TYPE => $tblPersonType->getId()
        ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return string
     */
    protected function tablePersonAllByType( TblPersonType $tblPersonType )
    {

        return self::extensionDataTables(
            $this->getEntityManager()->getEntity( 'TblPerson' ), array(
                'tblPersonType' => $tblPersonType->getId()
            )
        )
            ->setCallback( function ( TblPerson $V ) {

                /** @noinspection PhpUndefinedFieldInspection */
                $V->Option = ( new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
                    array( 'Id' => $V->getId() )
                ) )->__toString();
                return $V;
            } )
            ->getResult();
    }

    /**
     * @param string $Name
     *
     * @return bool|TblPersonType
     */
    protected function entityPersonTypeByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblPersonType' )->findOneBy( array(
            TblPersonType::ATTR_NAME => $Name
        ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonSalutation
     */
    protected function entityPersonSalutationById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPersonSalutation', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPersonSalutation[]
     */
    protected function entityPersonSalutationAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPersonSalutation' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonGender
     */
    protected function entityPersonGenderById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPersonGender', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPersonGender[]
     */
    protected function entityPersonGenderAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPersonGender' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonType
     */
    protected function entityPersonTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPersonType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPersonType[]
     */
    protected function entityPersonTypeAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPersonType' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonRelationshipType
     */
    protected function entityPersonRelationshipTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPersonRelationshipType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPersonRelationshipType[]
     */
    protected function entityPersonRelationshipTypeAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPersonRelationshipType' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

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
     * @param TblPerson $tblPerson
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
     * @return TblPerson
     */
    protected function actionCreatePerson(
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
        $Entity = $Manager->getEntity( 'TblPerson' )
            ->findOneBy( array(
                TblPerson::ATTR_TBL_PERSON_SALUTATION => $tblPersonSalutation->getId(),
                TblPerson::ATTR_TBL_PERSON_GENDER     => $tblPersonGender->getId(),
                TblPerson::ATTR_FIRST_NAME            => $FirstName,
                TblPerson::ATTR_LAST_NAME             => $LastName,
                TblPerson::ATTR_BIRTHDAY              => new \DateTime( $Birthday )
            ) );
        if (null === $Entity) {
            $Entity = new TblPerson();
            $Entity->setTblPersonSalutation( $tblPersonSalutation );
            $Entity->setFirstName( $FirstName );
            $Entity->setMiddleName( $MiddleName );
            $Entity->setLastName( $LastName );
            $Entity->setTblPersonGender( $tblPersonGender );
            $Entity->setBirthday( new \DateTime( $Birthday ) );
            $Entity->setBirthplace( $Birthplace );
            $Entity->setNationality( $Nationality );
            $Entity->setTblPersonType( $tblPersonType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

}
