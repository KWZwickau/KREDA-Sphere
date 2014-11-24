<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;
use KREDA\Sphere\Application\Service;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
 */
abstract class Setup extends Service
{

    /** @var EntityManager $EntityManager */
    protected static $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    private static $SchemaManager = null;
    /** @var null|Schema $Schema */
    private static $Schema = null;

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        /**
         * Table
         */
        $Schema = clone $this->loadSchema();
        $this->setTableToken( $Schema );
        /**
         * Migration
         */
        $Statement = $this->loadSchema()->getMigrateToSql( $Schema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                if (!$Simulate) {
                    $this->writeData()->prepareStatement( $Query )->executeWrite();
                }
            }
        }
        /**
         * View
         */
        if (!$this->dbHasView( 'viewToken' )) {
            $viewToken = $this->writeData()->getQueryBuilder()
                ->select( array(
                    'Id AS tblToken',
                    'Identifier AS TokenIdentifier',
                ) )
                ->from( 'tblToken' )
                ->getSQL();
            $this->addInstallProtocol( 'viewToken: '.$viewToken );
            $this->loadSchemaManager()->createView( new View( 'viewToken', $viewToken ) );
        }
        /**
         * Protocol
         */
        return $this->getInstallProtocol();
    }

    /**
     * @return Schema|null
     */
    private function loadSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$Schema) {
            self::$Schema = $this->loadSchemaManager()->createSchema();
        }
        return self::$Schema;
    }

    /**
     * @return AbstractSchemaManager|null
     */
    private function loadSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$SchemaManager) {
            self::$SchemaManager = $this->writeData()->getSchemaManager();
        }
        return self::$SchemaManager;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableToken( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblToken' )) {
            $Table = $Schema->createTable( 'tblToken' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblToken' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblToken', 'Identifier' )) {
            $Table->addColumn( 'Identifier', 'string' );
        }

        return $Table;
    }

    /**
     * @return EntityManager
     * @throws ORMException
     */
    protected function loadEntityManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$EntityManager) {
            self::$EntityManager = EntityManager::create(
                $this->readData()->getConnection(),
                ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
            );
        }
        return self::$EntityManager;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableToken()
    {

        return $this->loadSchema()->getTable( 'tblToken' );
    }
}
