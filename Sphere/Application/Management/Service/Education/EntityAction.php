<?php
namespace KREDA\Sphere\Application\Management\Service\Education;

use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectCategory;
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
            $Entity->setActiveState( true );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string    $Name
     * @param string    $FirstDateFrom
     * @param string    $FirstDateTo
     * @param string    $SecondDateFrom
     * @param string    $SecondDateTo
     * @param TblCourse $tblCourse
     *
     * @return TblTerm
     */
    protected function actionCreateTerm(
        $Name,
        $FirstDateFrom,
        $FirstDateTo,
        $SecondDateFrom,
        $SecondDateTo,
        TblCourse $tblCourse
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblTerm' )
            ->findOneBy( array( TblTerm::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblTerm( $Name );
            $Entity->setFirstDateFrom( new \DateTime( $FirstDateFrom ) );
            $Entity->setFirstDateTo( new \DateTime( $FirstDateTo ) );
            $Entity->setSecondDateFrom( new \DateTime( $SecondDateFrom ) );
            $Entity->setSecondDateTo( new \DateTime( $SecondDateTo ) );
            $Entity->setServiceManagementCourse( $tblCourse );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblTerm   $tblTerm
     * @param string    $Name
     * @param string    $FirstDateFrom
     * @param string    $FirstDateTo
     * @param string    $SecondDateFrom
     * @param string    $SecondDateTo
     * @param TblCourse $tblCourse
     *
     * @return bool
     */
    protected function actionChangeTerm(
        TblTerm $tblTerm,
        $Name,
        $FirstDateFrom,
        $FirstDateTo,
        $SecondDateFrom,
        $SecondDateTo,
        TblCourse $tblCourse
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblTerm $Entity */
        $Entity = $Manager->getEntityById( 'TblTerm', $tblTerm->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setName( $Name );
            $Entity->setFirstDateFrom( new \DateTime( $FirstDateFrom ) );
            $Entity->setFirstDateTo( new \DateTime( $FirstDateTo ) );
            $Entity->setSecondDateFrom( new \DateTime( $SecondDateFrom ) );
            $Entity->setSecondDateTo( new \DateTime( $SecondDateTo ) );
            $Entity->setServiceManagementCourse( $tblCourse );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
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
     * @param string $Description
     *
     * @return TblGroup
     */
    protected function actionCreateGroup( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblGroup' )
            ->findOneBy( array( TblGroup::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblGroup( $Name );
            $Entity->setDescription( $Description );
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
     * @param TblSubject  $tblSubject
     * @param TblCategory $tblCategory
     *
     * @return TblSubjectCategory
     */
    protected function actionAddSubjectCategory(
        TblSubject $tblSubject,
        TblCategory $tblCategory
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblSubjectCategory' )
            ->findOneBy( array(
                TblSubjectCategory::ATTR_TBL_SUBJECT  => $tblSubject->getId(),
                TblSubjectCategory::ATTR_TBL_CATEGORY => $tblCategory->getId(),
            ) );
        if (null === $Entity) {
            $Entity = new TblSubjectCategory();
            $Entity->setTblSubject( $tblSubject );
            $Entity->setTblCategory( $tblCategory );
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
     * @param TblCategory $tblCategory
     *
     * @return bool|TblSubject[]
     */
    protected function entitySubjectAllByCategory( TblCategory $tblCategory )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblSubjectCategory' )
            ->findBy( array( TblSubjectCategory::ATTR_TBL_CATEGORY => $tblCategory->getId() ) );
        if (!empty( $EntityList )) {
            array_walk( $EntityList, function ( TblSubjectCategory &$tblSubjectCategory ) {

                $tblSubjectCategory = $tblSubjectCategory->getTblSubject();
            } );
        }
        return ( null === $EntityList ? false : $EntityList );
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

    /**
     * @param string $Name
     *
     * @return bool|TblCategory
     */
    protected function entityCategoryByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblCategory' )
            ->findOneBy( array( TblCategory::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }
}
