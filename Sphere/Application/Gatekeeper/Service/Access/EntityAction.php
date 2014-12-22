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
    protected function actionCreateRight( $Route )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $Entity) {
            $Entity = new TblAccessRight( $Route );
            $Manager->saveEntity( $Entity );
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
        $Entity = $Manager->getEntity( 'TblAccessRightList' )
            ->findOneBy( array(
                TblAccessRightList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId(),
                TblAccessRightList::ATTR_TBL_ACCESS_RIGHT     => $TblAccessRight->getId()
            ) );
        if (null !== $Entity) {
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
        $Entity = $Manager->getEntity( 'TblAccessPrivilegeList' )
            ->findOneBy( array(
                TblAccessPrivilegeList::ATTR_TBL_ACCESS           => $tblAccess->getId(),
                TblAccessPrivilegeList::ATTR_TBL_ACCESS_PRIVILEGE => $TblAccessPrivilege->getId()
            ) );
        if (null !== $Entity) {
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

        $Entity = $this->getEntityManager()->getEntity( 'TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblAccess $Access
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByAccess( TblAccess $Access )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccess' => $Access->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }


    /**
     * @param TblAccessPrivilege $tblAccessPrivilege
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByPrivilege( TblAccessPrivilege $tblAccessPrivilege )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccessPrivilege' => $tblAccessPrivilege->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblAccessRight $tblAccessRight
     *
     * @return ViewAccess[]|bool
     */
    protected function entityViewAccessByRight( TblAccessRight $tblAccessRight )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'ViewAccess' )
            ->findBy( array( 'tblAccessRight' => $tblAccessRight->getId() ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccess
     */
    protected function entityAccessById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccess', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessPrivilege
     */
    protected function entityPrivilegeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccessPrivilege', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessRight
     */
    protected function entityRightById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccessRight', $Id );
        return ( null === $Entity ? false : $Entity );
    }
}
