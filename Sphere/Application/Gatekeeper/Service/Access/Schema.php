<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccountSession;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
class Schema extends Setup
{

    /** @var EntityManager $EntityManager */
    private $EntityManager = null;

    /**
     * @throws ORMException
     */
    function __construct()
    {

        $this->EntityManager = EntityManager::create(
            $this->readData()->getConnection(),
            ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
        );
    }

    /**
     * @param string $Username
     *
     * @return bool|integer
     */
    protected function schemaGetAccountIdByUsername( $Username )
    {

        /** @var TblAccount $tblAccount */
        $tblAccount = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $tblAccount) {
            return false;
        } else {
            return $tblAccount->getId();
        }
    }

    /**
     * @param string $Username
     * @param string $Password
     *
     * @return bool|integer
     */
    protected function schemaGetAccountIdByCredential( $Username, $Password )
    {

        /** @var TblAccount $tblAccount */
        $tblAccount = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
            ->findOneBy( array(
                TblAccount::ATTR_USERNAME => $Username,
                TblAccount::ATTR_PASSWORD => hash( 'sha256', $Password )
            ) );
        if (null === $tblAccount) {
            return false;
        } else {
            return $tblAccount->getId();
        }
    }

    /**
     * @param string $Session
     *
     * @return bool|integer
     */
    protected function schemaGetAccountIdBySession( $Session = null )
    {

        if (null === $Session) {
            $Session = session_id();
        }
        /** @var TblAccountSession $tblAccountSession */
        $tblAccountSession = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null === $tblAccountSession) {
            return false;
        } else {
            return $tblAccountSession->getTblAccount();
        }
    }

    /**
     * @param string $Route
     *
     * @return bool|null
     */
    protected function schemaCreateAccessRight( $Route )
    {

        $tblAccessRight = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $tblAccessRight) {
            $tblAccessRight = new TblAccessRight( $Route );
            $this->EntityManager->persist( $tblAccessRight );
            $this->EntityManager->flush();
            return true;
        }
        return null;
    }

    /**
     * @param string  $Session
     * @param integer $tblAccount
     * @param integer $Timeout
     *
     * @return bool
     */
    protected function schemaCreateSession(
        $Session,
        $tblAccount,
        $Timeout = 1800
    ) {

        $tblAccountSession = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $tblAccountSession) {
            $this->EntityManager->remove( $tblAccountSession );
        }
        $tblAccountSession = new TblAccountSession( $Session );
        $tblAccountSession->setTblAccount( $tblAccount );
        $tblAccountSession->setTimeout( time() + $Timeout );
        $this->EntityManager->persist( $tblAccountSession );
        $this->EntityManager->flush();
        return true;
    }

    /**
     * @param string       $Username
     * @param string       $Password
     * @param null|integer $tblYubiKey
     * @param null|integer $apiHumanResources_Person
     * @param null|integer $apiSystem_Consumer
     *
     * @return bool|null
     */
    protected function schemaCreateAccount(
        $Username,
        $Password,
        $tblYubiKey = null,
        $apiHumanResources_Person = null,
        $apiSystem_Consumer = null
    ) {

        $tblAccount = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $tblAccount) {
            $tblAccount = new TblAccount( $Username );
            $tblAccount->setPassword( hash( 'sha256', $Password ) );
            $tblAccount->setTblYubiKey( $tblYubiKey );
            $tblAccount->setApiHumanResourcesPerson( $apiHumanResources_Person );
            $tblAccount->setApiSystemConsumer( $apiSystem_Consumer );
            $this->EntityManager->persist( $tblAccount );
            $this->EntityManager->flush();
            return true;
        }
        return null;
    }
}
