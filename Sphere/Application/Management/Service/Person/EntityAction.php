<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use Doctrine\ORM\Query\Expr;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkDanger;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;

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
            ->getQuery()
            ->useQueryCache( true );
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
            ->getQuery()
            ->useQueryCache( true );
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
     * @param int $tblPerson
     *
     * @return string
     */
    protected function tablePersonRelationship( $tblPerson )
    {

        $tblPersonRelationshipType = Management::servicePerson()->entityPersonRelationshipTypeAll();

        return self::extensionDataTables(
            $this->getEntityManager()->getEntity( 'TblPerson' )
        )
            ->setCallbackFunction( function ( TblPerson $V, $P ) {

                /** @noinspection PhpUndefinedFieldInspection */
                $V->Name = $V->getFullName();

                /** @noinspection PhpUndefinedFieldInspection */
                $V->Option = ( new FormDefault(
                    new GridFormGroup(
                        new GridFormRow( array(
                            new GridFormCol(
                                new InputSelect( 'tblRelationshipType', '',
                                    array( 'Name' => $P[1] )
                                )
                                , 7 ),
                            new GridFormCol(
                                new ButtonSubmitPrimary( 'Hinzufügen', new ShareIcon() )
                                , 5 )
                        ) )
                    ), null,
                    '/Sphere/Management/Person/Relationship', array(
                        'tblPerson'       => $P[0],
                        'tblRelationship' => $V->getId()
                    )
                ) )->__toString();

//                $V->Option =
//                    ( new ButtonLinkPrimary( 'Auswählen', '/Sphere/Management/Person/Relationship', new PencilIcon(),
//                        array(
//                            'tblPerson'       => $P,
//                            'tblRelationship' => $V->getId()
//                        )
//                    ) )->__toString();
                return $V;
            }, array( $tblPerson, $tblPersonRelationshipType ) )
            ->getResult();
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
            ->setCallbackFunction( function ( TblPerson $V ) {

                /** @noinspection PhpUndefinedFieldInspection */
                $V->Option =
                    ( new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', new PencilIcon(),
                        array( 'Id' => $V->getId() )
                    ) )->__toString()
                    .( new ButtonLinkDanger( 'Löschen', '/Sphere/Management/Person/Destroy', new RemoveIcon(),
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
     * @param TblPerson $tblPerson
     *
     * @return bool|Entity\TblPersonRelationshipList[]
     */
    protected function entityPersonRelationshipAllByPerson( TblPerson $tblPerson )
    {

        $EntityListA = $this->getEntityManager()->getEntity( 'TblPersonRelationshipList' )->findBy( array(
            TblPersonRelationshipList::ATTR_TBL_PERSON_A => $tblPerson->getId()
        ) );
        $EntityListB = $this->getEntityManager()->getEntity( 'TblPersonRelationshipList' )->findBy( array(
            TblPersonRelationshipList::ATTR_TBL_PERSON_B => $tblPerson->getId()
        ) );
        if (is_array( $EntityListA ) && is_array( $EntityListB )) {
            $EntityList = $EntityListA + $EntityListB;
        } else {
            if (is_array( $EntityListA )) {
                $EntityList = $EntityListA;
            } else {
                if (is_array( $EntityListB )) {
                    $EntityList = $EntityListB;
                } else {
                    $EntityList = null;
                }
            }
        }
        return ( empty( $EntityList ) ? false : $EntityList );
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
     * @param string    $Title
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
