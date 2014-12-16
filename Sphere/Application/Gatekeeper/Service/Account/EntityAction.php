<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountTyp;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string           $Username
     * @param string           $Password
     * @param TblAccountTyp    $tblAccountTyp
     * @param TblAccountRole   $tblAccountRole
     * @param null|TblToken    $tblToken
     * @param null|TblPerson   $tblPerson
     * @param null|TblConsumer $tblConsumer
     *
     * @return TblAccount
     */
    protected function actionCreateAccount(
        $Username,
        $Password,
        $tblAccountTyp,
        $tblAccountRole = null,
        $tblToken = null,
        $tblPerson = null,
        $tblConsumer = null
    ) {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            $Entity = new TblAccount( $Username );
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Entity->setTblAccountTyp( $tblAccountTyp );
            $Entity->setTblAccountRole( $tblAccountRole );
            $Entity->setServiceGatekeeperToken( $tblToken );
            $Entity->setServiceManagementPerson( $tblPerson );
            $Entity->setServiceGatekeeperConsumer( $tblConsumer );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByUsername( $Username )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblAccount
     */
    protected function entityAccountBySession( $Session = null )
    {

        if (null === $Session) {
            $Session = session_id();
        }
        /** @var TblAccountSession $Entity */
        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null === $Entity) {
            return false;
        } else {
            return $this->entityAccountById( $Entity->getTblAccount() );
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    protected function entityAccountById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccount', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    protected function entityAccountTypById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccountTyp', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountRole
     */
    protected function entityAccountRoleById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccountRole', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Username
     * @param string $Password
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByCredential( $Username, $Password )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array(
                TblAccount::ATTR_USERNAME => $Username,
                TblAccount::ATTR_PASSWORD => hash( 'sha256', $Password )
            ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblAccount  $tblAccount
     * @param null|string $Session
     * @param integer     $Timeout
     *
     * @return TblAccountSession
     */
    protected function actionCreateSession( TblAccount $tblAccount, $Session = null, $Timeout = 1800 )
    {

        if (null === $Session) {
            $Session = session_id();
        }

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->getDatabaseHandler()->getEntityManager()->killEntity( $Entity );
        }
        $Entity = new TblAccountSession( $Session );
        $Entity->setTblAccount( $tblAccount );
        $Entity->setTimeout( time() + $Timeout );
        $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccountTyp
     */
    protected function actionCreateAccountTyp( $Name )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountTyp' )
            ->findOneBy( array( TblAccountTyp::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountTyp();
            $Entity->setName( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccountRole
     */
    protected function actionCreateAccountRole( $Name )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountRole' )
            ->findOneBy( array( TblAccountRole::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountRole();
            $Entity->setName( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param null|string $Session
     *
     * @return bool
     */
    protected function actionDestroySession( $Session = null )
    {

        if (null === $Session) {
            $Session = session_id();
        }

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->getDatabaseHandler()->getEntityManager()->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
