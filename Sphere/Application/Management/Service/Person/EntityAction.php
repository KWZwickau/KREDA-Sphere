<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

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
     * @param string $Salutation
     * @param string $FirstName
     * @param string $MiddleName
     * @param string $LastName
     * @param string $Gender
     * @param string $Birthday
     *
     * @return TblPerson
     */
    protected function actionCreatePerson( $Salutation, $FirstName, $MiddleName, $LastName, $Gender, $Birthday )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPerson' )
            ->findOneBy( array(
                TblPerson::ATTR_FIRST_NAME => $FirstName,
                TblPerson::ATTR_LAST_NAME  => $LastName,
                TblPerson::ATTR_BIRTHDAY   => $Birthday
            ) );
        if (null === $Entity) {
            $Entity = new TblPerson();
            $Entity->setSalutation( $Salutation );
            $Entity->setFirstName( $FirstName );
            $Entity->setMiddleName( $MiddleName );
            $Entity->setLastName( $LastName );
            $Entity->setGender( $Gender );
            $Entity->setBirthday( $Birthday );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

}
