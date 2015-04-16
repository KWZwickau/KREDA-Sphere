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
     * @param string $Name
     * @param string $FirstDateFrom
     * @param string $FirstDateTo
     * @param string $SecondDateFrom
     * @param string $SecondDateTo
     *
     * @return TblTerm
     */
    protected function actionCreateTerm( $Name, $FirstDateFrom, $FirstDateTo, $SecondDateFrom, $SecondDateTo )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblTerm' )
            ->findOneBy( array( TblTerm::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblTerm( $Name );
            $Entity->setFirstDateFrom( new \DateTime( $FirstDateFrom ) );
            $Entity->setFirstDateTo( new \DateTime( $FirstDateTo ) );
            $Entity->setSecondDateFrom( new \DateTime( $SecondDateFrom ) );
            $Entity->setSecondDateTo( new \DateTime( $SecondDateTo ) );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblCategory
     */
    protected function actionCreateCategory( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblCategory' )
            ->findOneBy( array( TblCategory::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblCategory( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblGroup
     */
    protected function actionCreateGroup( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblGroup' )
            ->findOneBy( array( TblGroup::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblGroup( $Name );
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
     * @return TblLevel
     */
    protected function actionCreateLevel( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblLevel' )
            ->findOneBy( array( TblLevel::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblLevel( $Name );
            $Entity->setDescription( $Description );
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
     * @param string $Name
     *
     * @return bool|TblTerm
     */
    protected function entityTermByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblTerm' )
            ->findOneBy( array( TblTerm::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblLevel
     */
    protected function entityLevelByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblLevel' )
            ->findOneBy( array( TblLevel::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblGroup
     */
    protected function entityGroupByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblGroup' )
            ->findOneBy( array( TblGroup::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Acronym
     *
     * @return bool|TblSubject
     */
    protected function entitySubjectByAcronym( $Acronym )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblSubject' )
            ->findOneBy( array( TblSubject::ATTR_ACRONYM => $Acronym ) );
        return ( null === $Entity ? false : $Entity );
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
     * @return bool|TblSubjectGroup[]
     */
    protected function entitySubjectGroupAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblSubjectGroup' )->findAll();
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
