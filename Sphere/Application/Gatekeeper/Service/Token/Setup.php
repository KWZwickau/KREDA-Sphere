<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
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
    protected $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    private $SchemaManager = null;
    /** @var null|Schema $Schema */
    private $Schema = null;

    /**
     * @throws ORMException
     */
    function __construct()
    {

        $this->SchemaManager = $this->writeData()->getSchemaManager();
        $this->Schema = $this->SchemaManager->createSchema();
        $this->EntityManager = EntityManager::create(
            $this->readData()->getConnection(),
            ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
        );
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        /**
         * Setup
         */
        $Schema = clone $this->Schema;
        $this->setTableToken( $Schema );
        /**
         * Migration
         */
        $Statement = $this->Schema->getMigrateToSql( $Schema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        /**
         * Execute
         */
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                if (!$Simulate) {
                    $this->writeData()->prepareStatement( $Query )->executeWrite();
                }
            }
        }
        return $this->getInstallProtocol();
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
     * @return Table
     * @throws SchemaException
     */
    protected function getTableToken()
    {

        return $this->Schema->getTable( 'tblToken' );
    }
}
