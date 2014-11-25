<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema\TblAccountSession;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
class Schema extends Setup
{

    /**
     * @param string       $Username
     * @param string       $Password
     * @param null|integer $tblToken
     * @param null|integer $apiHumanResources_Person
     * @param null|integer $apiSystem_Consumer
     *
     * @return bool|null
     */
    protected function actionCreateAccount(
        $Username,
        $Password,
        $tblToken = null,
        $apiHumanResources_Person = null,
        $apiSystem_Consumer = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $tblAccount = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $tblAccount) {
            $tblAccount = new TblAccount( $Username );
            $tblAccount->setPassword( hash( 'sha256', $Password ) );
            $tblAccount->setTblToken( $tblToken );
            $tblAccount->setApiHumanResourcesPerson( $apiHumanResources_Person );
            $tblAccount->setApiSystemConsumer( $apiSystem_Consumer );
            $this->loadEntityManager()->persist( $tblAccount );
            $this->loadEntityManager()->flush();
            return true;
        }
        return null;
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    protected function objectAccountByUsername( $Username )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
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
        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
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
        $Entity = $this->loadEntityManager()->find( __NAMESPACE__.'\Schema\TblAccount', $Id );
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

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
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
     * @return bool
     */
    protected function actionCreateSession( $Session, $tblAccount, $Timeout = 1800 )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->loadEntityManager()->remove( $Entity );
        }
        $Entity = new TblAccountSession( $Session );
        $Entity->setTblAccount( $tblAccount );
        $Entity->setTimeout( time() + $Timeout );
        $this->loadEntityManager()->persist( $Entity );
        $this->loadEntityManager()->flush();
        return true;
    }

    /**
     * @param null|string $Session
     *
     * @return bool
     */
    protected function actionDestroySession( $Session = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === $Session) {
            $Session = session_id();
        }

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            $this->loadEntityManager()->remove( $Entity );
            $this->loadEntityManager()->flush();
            return true;
        }
        return false;
    }
}
