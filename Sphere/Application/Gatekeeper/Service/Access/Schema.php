<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\tblAccount;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
class Schema extends Setup
{

    /**
     * @param string $Username
     *
     * @return bool|integer
     */
    protected function schemaGetAccountIdByUsername( $Username )
    {

        $Get = $this->readData()->getQueryBuilder();
        return $Get->select( 'Id' )->from( 'tblAccount' )->where( 'Username = ?' )
            ->setParameter( 0, $Username )
            ->execute()->fetchColumn();
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
        $Get = $this->readData()->getQueryBuilder();
        return $Get->select( 'tblAccount' )->from( 'tblAccountSession' )->where( 'Session = ?' )
            ->setParameter( 0, $Session )
            ->execute()->fetchColumn();
    }

    /**
     * @param string $Route
     *
     * @return bool|null
     */
    protected function schemaCreateAccessRight( $Route )
    {

        $Get = $this->readData()->getQueryBuilder();
        $Set = $this->writeData()->getQueryBuilder();
        if (false == $Get->select( 'Id' )->from( 'tblAccessRight' )
                ->where( 'Route = ?' )
                ->setParameter( 0, $Route )
                ->execute()->fetch()
        ) {
            if ($Set->insert( 'tblAccessRight' )
                ->values( array(
                    'Route' => '?',
                ) )
                ->setParameter( 0, $Route, Type::STRING )
                ->execute()
            ) {
                return true;
            } else {
                return false;
            }
        }
        return null;
    }

    protected function schemaCreateAccessPrivilege()
    {

        $Get = $this->readData()->getQueryBuilder();
        $Set = $this->writeData()->getQueryBuilder();
    }

    protected function schemaAddAccessRightPrivilege()
    {

        $Get = $this->readData()->getQueryBuilder();
        $Set = $this->writeData()->getQueryBuilder();
    }

    protected function schemaCreateAccessRole()
    {

        $Get = $this->readData()->getQueryBuilder();
        $Set = $this->writeData()->getQueryBuilder();

    }

    protected function schemaAddAccessPrivilegeRole()
    {

        $Get = $this->readData()->getQueryBuilder();
        $Set = $this->writeData()->getQueryBuilder();

    }

    /**
     * @param string       $Username
     * @param string       $Password
     * @param null|integer $tblYubiKey
     * @param null|integer $apiHumanResources_Person
     * @param null|integer $apiSystem_Consumer
     *
     * @return bool|null
     * @throws ORMException
     */
    protected function schemaCreateAccount(
        $Username,
        $Password,
        $tblYubiKey = null,
        $apiHumanResources_Person = null,
        $apiSystem_Consumer = null
    ) {

        $EntityManager = EntityManager::create(
            $this->readData()->getConnection(),
            ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
        );

        $tblAccount = $EntityManager->getRepository( __NAMESPACE__.'\Schema\tblAccount' )
            ->findOneBy( array( tblAccount::USERNAME => $Username ) );
        if (null === $tblAccount) {
            $tblAccount = new tblAccount( $Username );
            $tblAccount->setPassword( hash( 'sha256', $Password ) );
            $tblAccount->setTblYubiKey( $tblYubiKey );
            $tblAccount->setApiHumanResourcesPerson( $apiHumanResources_Person );
            $tblAccount->setApiSystemConsumer( $apiSystem_Consumer );
            $EntityManager->persist( $tblAccount );
            $EntityManager->flush();
            return true;
        }

        return null;
    }
}
