<?php
namespace KREDA\Sphere\Application\Management\Service\Education;

use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Education
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Acronym
     * @param string $Name
     *
     * @return TblSubject
     */
    protected function actionCreateSubject( $Acronym, $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblSubject' )
            ->findOneBy( array( TblSubject::ATTR_ACRONYM => $Acronym ) );
        if (null === $Entity) {
            $Entity = new TblSubject( $Acronym );
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @return bool|TblTerm[]
     */
    protected function entityTermAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblTerm' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return bool|TblSubject[]
     */
    protected function entitySubjectAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblSubject' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return bool|TblLevel[]
     */
    protected function entityLevelAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblLevel' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return bool|TblGroup[]
     */
    protected function entityGroupAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblGroup' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblTerm
     */
    protected function entityTermById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblTerm', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblLevel
     */
    protected function entityLevelById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblLevel', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblGroup
     */
    protected function entityGroupById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblGroup', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblSubject
     */
    protected function entitySubjectById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblSubject', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblSubjectGroup
     */
    protected function entitySubjectGroupById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblSubjectGroup', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblCategory
     */
    protected function entityCategoryById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblCategory', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }
}
