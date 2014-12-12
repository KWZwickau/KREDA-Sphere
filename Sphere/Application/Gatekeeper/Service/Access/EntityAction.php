<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRightList;
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
     * @return TblAccess
     */
    protected function actionCreateAccess( $Name )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccess' )
            ->findOneBy( array( TblAccess::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccess( $Name );
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
     * @return TblAccessRightList
     */
    protected function actionCreateAccessRightList(
        TblAccessRight $TblAccessRight,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessRightList' )
            ->findOneBy( array(
                TblAccessRightList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId(),
                TblAccessRightList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessRightList();
            $Entity->setTblAccessRight( $TblAccessRight );
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccess          $tblAccess
     *
     * @return TblAccessPrivilegeList
     */
    protected function actionCreateAccessPrivilegeList(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccess $tblAccess
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccessPrivilegeList' )
            ->findOneBy( array(
                TblAccessPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessPrivilegeList::ATTR_TBL_ACCESS           => $tblAccess->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccessPrivilegeList();
            $Entity->setTblAccessPrivilege( $TblAccessPrivilege );
            $Entity->setTblAccess( $tblAccess );
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
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblAccess $Access
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByAccessRole( TblAccess $Access )
    {

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccess' => $Access->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }


    /**
     * @param TblAccessPrivilege $AccessPrivilege
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByAccessPrivilege( TblAccessPrivilege $AccessPrivilege )
    {

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccessPrivilege' => $AccessPrivilege->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblAccessRight $AccessRight
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByAccessRight( TblAccessRight $AccessRight )
    {

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccessRight' => $AccessRight->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccess
     */
    protected function entityAccessById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccess', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessPrivilege
     */
    protected function entityAccessPrivilegeById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccessPrivilege', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessRight
     */
    protected function entityAccessRightById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccessRight', $Id );
        return ( null === $Entity ? false : $Entity );
    }
}
