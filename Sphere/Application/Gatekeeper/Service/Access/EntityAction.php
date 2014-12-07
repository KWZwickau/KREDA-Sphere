<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilegeRoleList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRightPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\ViewAccess;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Route
     *
     * @return TblAccessRight
     */
    protected function actionCreateAccessRight( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $Entity) {
            $Entity = new TblAccessRight( $Route );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessRole' )
            ->findOneBy( array( TblAccessRole::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccessRole( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessPrivilege' )
            ->findOneBy( array( TblAccessPrivilege::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilege( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessRightPrivilegeList' )
            ->findOneBy( array(
                TblAccessRightPrivilegeList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId(),
                TblAccessRightPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessRightPrivilegeList();
            $Entity->setTblAccessRight( $TblAccessRight->getId() );
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege->getId() );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessPrivilegeRoleList' )
            ->findOneBy( array(
                TblAccessPrivilegeRoleList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessPrivilegeRoleList::ATTR_TBL_ACCESS_ROLE      => $tblAccessRole->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilegeRoleList();
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege->getId() );
            $Entity->setTblAccessRole( $tblAccessRole->getId() );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Route
     *
     * @return bool|TblAccessRight
     */
    protected function entityAccessRightByRouteName( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessRight' )
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
    protected function entityViewAccessByAccessRole( TblAccessRole $AccessRole )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
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
    protected function entityViewAccessByAccessPrivilege( TblAccessPrivilege $AccessPrivilege )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
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
    protected function entityViewAccessByAccessRight( TblAccessRight $AccessRight )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccessRight' => $AccessRight->getId() ) );
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }
}
