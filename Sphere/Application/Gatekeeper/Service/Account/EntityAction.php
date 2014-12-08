<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountTyp;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string        $Username
     * @param string        $Password
     * @param TblAccountTyp $tblAccountTyp
     * @param null|TblToken $serviceGatekeeper_Token
     * @param null|integer  $serviceHumanResources_Person
     * @param null|integer  $serviceGatekeeper_Consumer
     *
     * @return TblAccount
     */
    protected function actionCreateAccount(
        $Username,
        $Password,
        $tblAccountTyp,
        $serviceGatekeeper_Token = null,
        $serviceHumanResources_Person = null,
        $serviceGatekeeper_Consumer = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            $Entity = new TblAccount( $Username );
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Entity->setTblAccountTyp( $tblAccountTyp );
            $Entity->setServiceGatekeeperToken( $serviceGatekeeper_Token );
            $Entity->setServiceHumanResourcesPerson( $serviceHumanResources_Person );
            $Entity->setServiceGatekeeperConsumer( $serviceGatekeeper_Consumer );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccount    $tblAccount
     * @param null|TblToken $tblToken
     *
     * @return mixed
     */
    protected function actionSetAccountToken(
        TblAccount $tblAccount,
        TblToken $tblToken = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $tblAccount->setServiceGatekeeperToken( $tblToken );
        $this->getDatabaseHandler()->getEntityManager()->saveEntity( $tblAccount );
        return $tblAccount;
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByUsername( $Username )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblAccount
     */
    protected function entityAccountBySession( $Session = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

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

        $this->getDebugger()->addMethodCall( __METHOD__ );
        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccount', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    protected function entityAccountTypById( $Id )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAccountTyp', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param string $Username
     * @param string $Password
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByCredential( $Username, $Password )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array(
                TblAccount::ATTR_USERNAME => $Username,
                TblAccount::ATTR_PASSWORD => hash( 'sha256', $Password )
            ) );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param string  $Session
     * @param integer $tblAccount
     * @param integer $Timeout
     *
     * @return TblAccountSession
     */
    protected function actionCreateSession( $Session, $tblAccount, $Timeout = 1800 )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

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

        $this->getDebugger()->addMethodCall( __METHOD__ );

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
     * @param null|string $Session
     *
     * @return TblAccountSession
     */
    protected function actionDestroySession( $Session = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === $Session) {
            $Session = session_id();
        }

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }
}
