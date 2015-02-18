<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRightList;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class EntityAction extends EntitySchema
{

    /** @var TblAccessRight[] $EntityAccessByIdCache */
    private static $EntityRouteByIdCache = array();
    /** @var TblAccess[] $EntityAccessByIdCache */
    private static $EntityAccessByIdCache = array();
    /** @var TblAccessPrivilege[] $EntityPrivilegeByIdCache */
    private static $EntityPrivilegeByIdCache = array();
    /** @var TblAccessPrivilege[] $EntityPrivilegeByAccessCache */
    private static $EntityPrivilegeByAccessCache = array();
    /** @var TblAccessRight[] $EntityRightByIdCache */
    private static $EntityRightByIdCache = array();
    /** @var TblAccessRight[] $EntityRightByPrivilegeCache */
    private static $EntityRightByPrivilegeCache = array();

    /**
     * @param string $Route
     *
     * @return TblAccessRight
     */
    protected function actionCreateRight( $Route )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $Entity) {
            $Entity = new TblAccessRight( $Route );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccess
     */
    protected function actionCreateAccess( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccess' )
            ->findOneBy( array( TblAccess::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccess( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccessPrivilege
     */
    protected function actionCreatePrivilege( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccessPrivilege' )
            ->findOneBy( array( TblAccessPrivilege::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilege( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccessRight     $TblAccessRight
     *
     * @return TblAccessRightList
     */
    protected function actionAddPrivilegeRight(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccessRight $TblAccessRight
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccessRightList' )
            ->findOneBy( array(
                TblAccessRightList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessRightList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessRightList();
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege );
            $Entity->setTblAccessRight( $TblAccessRight );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccessRight     $TblAccessRight
     *
     * @return bool
     */
    protected function actionRemovePrivilegeRight(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccessRight $TblAccessRight
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblAccessRightList $Entity */
        $Entity = $Manager->getEntity( 'TblAccessRightList' )
            ->findOneBy( array(
                TblAccessRightList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessRightList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblAccess          $tblAccess
     * @param TblAccessPrivilege $TblAccessPrivilege
     *
     * @return TblAccessPrivilegeList
     */
    protected function actionAddAccessPrivilege(
        TblAccess $tblAccess,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccessPrivilegeList' )
            ->findOneBy( array(
                TblAccessPrivilegeList::ATTR_TBL_ACCESS           => $tblAccess->getId(),
                TblAccessPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilegeList();
            $Entity->setTblAccess( $tblAccess );
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccess          $tblAccess
     * @param TblAccessPrivilege $TblAccessPrivilege
     *
     * @return bool
     */
    protected function actionRemoveAccessPrivilege(
        TblAccess $tblAccess,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblAccessPrivilegeList $Entity */
        $Entity = $Manager->getEntity( 'TblAccessPrivilegeList' )
            ->findOneBy( array(
                TblAccessPrivilegeList::ATTR_TBL_ACCESS           => $tblAccess->getId(),
                TblAccessPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccessRight
     */
    protected function entityAccessRightByRouteName( $Name )
    {

        if (isset( self::$EntityRouteByIdCache[$Name] )) {
            return self::$EntityRouteByIdCache[$Name];
        }
        $Entity = $this->getEntityManager()->getEntity( 'TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Name ) );
        self::$EntityRouteByIdCache[$Name] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccess
     */
    protected function entityAccessById( $Id )
    {

        if (isset( self::$EntityAccessByIdCache[$Id] )) {
            return self::$EntityAccessByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccess', $Id );
        self::$EntityAccessByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccess
     */
    protected function entityAccessByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccess' )
            ->findOneBy( array( TblAccess::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessPrivilege
     */
    protected function entityPrivilegeById( $Id )
    {

        if (isset( self::$EntityPrivilegeByIdCache[$Id] )) {
            return self::$EntityPrivilegeByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccessPrivilege', $Id );
        self::$EntityPrivilegeByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     *
     * @param TblAccess $tblAccess
     *
     * @return bool|TblAccessPrivilege[]
     */
    protected function entityPrivilegeAllByAccess( TblAccess $tblAccess )
    {

        if (isset( self::$EntityPrivilegeByAccessCache[$tblAccess->getId()] )) {
            return self::$EntityPrivilegeByAccessCache[$tblAccess->getId()];
        }
        /** @var TblAccessPrivilegeList[] $EntityList */
        $EntityList = $this->getEntityManager()->getEntity( 'TblAccessPrivilegeList' )->findBy( array(
            TblAccessPrivilegeList::ATTR_TBL_ACCESS => $tblAccess->getId()
        ) );
        array_walk( $EntityList, function ( TblAccessPrivilegeList &$V ) {

            $V = $V->getTblAccessPrivilege();
        } );
        self::$EntityPrivilegeByAccessCache[$tblAccess->getId()] = $EntityList;
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     *
     * @param TblAccessPrivilege $tblAccessPrivilege
     *
     * @return bool|TblAccessRight[]
     */
    protected function entityRightAllByPrivilege( TblAccessPrivilege $tblAccessPrivilege )
    {

        if (isset( self::$EntityRightByPrivilegeCache[$tblAccessPrivilege->getId()] )) {
            return self::$EntityRightByPrivilegeCache[$tblAccessPrivilege->getId()];
        }
        /** @var TblAccessRightList[] $EntityList */
        $EntityList = $this->getEntityManager()->getEntity( 'TblAccessRightList' )->findBy( array(
            TblAccessRightList::ATTR_TBL_ACCESS_PRIVILEGE => $tblAccessPrivilege->getId()
        ) );
        array_walk( $EntityList, function ( TblAccessRightList &$V ) {

            $V = $V->getTblAccessRight();
        } );
        self::$EntityRightByPrivilegeCache[$tblAccessPrivilege->getId()] = $EntityList;
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessRight
     */
    protected function entityRightById( $Id )
    {

        if (isset( self::$EntityRightByIdCache[$Id] )) {
            return self::$EntityRightByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccessRight', $Id );
        self::$EntityRightByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccessRight[]
     */
    protected function entityRightAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccessRight' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return bool|TblAccessPrivilege[]
     */
    protected function entityPrivilegeAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccessPrivilege' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return bool|TblAccess[]
     */
    protected function entityAccessAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccess' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
