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

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            $Entity = new TblAccount( $Username );
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Entity->setTblAccountTyp( $tblAccountTyp );
            $Entity->setTblAccountRole( $tblAccountRole );
            $Entity->setServiceGatekeeperToken( $tblToken );
            $Entity->setServiceManagementPerson( $tblPerson );
            $Entity->setServiceGatekeeperConsumer( $tblConsumer );
            $Manager->saveEntity( $Entity );
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

        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )
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
        $Entity = $this->getEntityManager()->getEntity( 'TblAccountSession' )
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

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccount', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountTyp
     */
    protected function entityAccountTypById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountTyp', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountTyp
     */
    protected function entityAccountTypByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccountTyp' )
            ->findOneBy( array(
                TblAccountTyp::ATTR_NAME => $Name
            ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountRole
     */
    protected function entityAccountRoleById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountRole', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string        $Username
     * @param string        $Password
     * @param TblAccountTyp $tblAccountTyp
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByCredential( $Username, $Password, TblAccountTyp $tblAccountTyp )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array(
                TblAccount::ATTR_USERNAME        => $Username,
                TblAccount::ATTR_PASSWORD        => hash( 'sha256', $Password ),
                TblAccount::ATTR_TBL_ACCOUNT_TYP => $tblAccountTyp->getId()
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
        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $Manager->killEntity( $Entity );
        }
        $Entity = new TblAccountSession( $Session );
        $Entity->setTblAccount( $tblAccount );
        $Entity->setTimeout( time() + $Timeout );
        $Manager->saveEntity( $Entity );
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccountTyp
     */
    protected function actionCreateAccountTyp( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountTyp' )
            ->findOneBy( array( TblAccountTyp::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountTyp();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
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

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountRole' )
            ->findOneBy( array( TblAccountRole::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountRole();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
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

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
