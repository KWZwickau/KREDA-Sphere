<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Action;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\Management\Service\Person\EntitySchema;

/**
 * Class Entity
 *
 * @package KREDA\Sphere\Application\Management\Service\Person\Action
 */
abstract class Entity extends EntitySchema
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
     * @param TblPersonType $tblPersonType
     *
     * @return bool|TblPerson[]
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
     * @return bool|TblPersonRelationshipList[]
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
            $EntityList = array_merge( $EntityListA, $EntityListB );
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
     * @param integer $Id
     *
     * @return bool|TblPersonRelationshipList
     */
    protected function entityPersonRelationshipById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPersonRelationshipList', $Id );
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
     * @param TblPerson $tblPerson
     *
     * @return bool|TblAddress[]
     */
    protected function entityAddressAllByPerson( TblPerson $tblPerson )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPersonAddress' )->findBy( array(
            TblPersonAddress::ATTR_TBL_PERSON => $tblPerson->getId()
        ) );

        if (!empty( $EntityList )) {
            array_walk( $EntityList, function ( TblPersonAddress &$Entity ) {

                $Entity = $Entity->getTblAddress();
            } );
        }

        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
