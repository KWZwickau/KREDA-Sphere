<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
abstract class Schema extends Setup
{

    /**
     * @param string       $Username
     * @param string       $Password
     * @param null|integer $tblToken
     * @param null|integer $apiHumanResources_Person
     * @param null|integer $apiSystem_Consumer
     *
     * @return TblAccount
     */
    protected function actionCreateAccount(
        $Username,
        $Password,
        $tblToken = null,
        $apiHumanResources_Person = null,
        $apiSystem_Consumer = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            $Entity = new TblAccount( $Username );
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Entity->setTblToken( $tblToken );
            $Entity->setApiHumanResourcesPerson( $apiHumanResources_Person );
            $Entity->setApiSystemConsumer( $apiSystem_Consumer );
            $this->getEntityManager()->persist( $Entity );
            $this->getEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @param TblAccount    $tblAccount
     * @param null|\KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken $tblToken
     *
     * @return mixed
     */
    protected function actionSetAccountToken(
        TblAccount $tblAccount,
        TblToken $tblToken = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $tblAccount->setTblToken( $tblToken );
        $this->getEntityManager()->persist( $tblAccount );
        $this->getEntityManager()->flush();
        return $tblAccount;
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    protected function objectAccountByUsername( $Username )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
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
    protected function objectAccountBySession( $Session = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === $Session) {
            $Session = session_id();
        }
        /** @var TblAccountSession $Entity */
        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null === $Entity) {
            return false;
        } else {
            return $this->objectAccountById( $Entity->getTblAccount() );
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    protected function objectAccountById( $Id )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        $Entity = $this->getEntityManager()->find( __NAMESPACE__.'\Schema\TblAccount', $Id );
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
    protected function objectAccountByCredential( $Username, $Password )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
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

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->getEntityManager()->remove( $Entity );
        }
        $Entity = new TblAccountSession( $Session );
        $Entity->setTblAccount( $tblAccount );
        $Entity->setTimeout( time() + $Timeout );
        $this->getEntityManager()->persist( $Entity );
        $this->getEntityManager()->flush();
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

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->getEntityManager()->remove( $Entity );
            $this->getEntityManager()->flush();
        }
        return $Entity;
    }
}
