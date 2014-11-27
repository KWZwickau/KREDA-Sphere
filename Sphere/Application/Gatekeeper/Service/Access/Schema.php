<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessPrivilegeRoleList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessRightPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\ViewAccess;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class Schema extends Setup
{

    /**
     * @param string $Route
     *
     * @return TblAccessRight
     */
    protected function actionCreateAccessRight( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $Entity) {
            $Entity = new TblAccessRight( $Route );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccessRole
     */
    protected function actionCreateAccessRole( $Name )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessRole' )
            ->findOneBy( array( TblAccessRole::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccessRole( $Name );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccessPrivilege
     */
    protected function actionCreateAccessPrivilege( $Name )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessPrivilege' )
            ->findOneBy( array( TblAccessPrivilege::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilege( $Name );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param TblAccessRight     $TblAccessRight
     * @param TblAccessPrivilege $TblAccessPrivilege
     *
     * @return TblAccessRightPrivilegeList
     */
    protected function actionCreateAccessRightPrivilegeList(
        TblAccessRight $TblAccessRight,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessRightPrivilegeList' )
            ->findOneBy( array(
                TblAccessRightPrivilegeList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId(),
                TblAccessRightPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessRightPrivilegeList();
            $Entity->setTblAccessRight( $TblAccessRight->getId() );
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege->getId() );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccessRole      $tblAccessRole
     *
     * @return TblAccessPrivilegeRoleList
     */
    protected function actionCreateAccessPrivilegeRoleList(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccessRole $tblAccessRole
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessPrivilegeRoleList' )
            ->findOneBy( array(
                TblAccessPrivilegeRoleList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessPrivilegeRoleList::ATTR_TBL_ACCESS_ROLE      => $tblAccessRole->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilegeRoleList();
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege->getId() );
            $Entity->setTblAccessRole( $tblAccessRole->getId() );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param string $Route
     *
     * @return bool|TblAccessRight
     */
    protected function objectAccessRightByRouteName( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param TblAccessRole $AccessRole
     *
     * @return ViewAccess[]|bool
     */
    protected function objectViewAccessByAccessRole( TblAccessRole $AccessRole )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\ViewAccess' )
            ->findBy( array( 'tblAccessRole' => $AccessRole->getId() ) );
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }


    /**
     * @param TblAccessPrivilege $AccessPrivilege
     *
     * @return ViewAccess[]|bool
     */
    protected function objectViewAccessByAccessPrivilege( TblAccessPrivilege $AccessPrivilege )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\ViewAccess' )
            ->findBy( array( 'tblAccessPrivilege' => $AccessPrivilege->getId() ) );
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }

    /**
     * @param TblAccessRight $AccessRight
     *
     * @return ViewAccess[]|bool
     */
    protected function objectViewAccessByAccessRight( TblAccessRight $AccessRight )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\ViewAccess' )
            ->findBy( array( 'tblAccessRight' => $AccessRight->getId() ) );
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }
}
