<?php
namespace KREDA\Sphere\Application\System\Service\Consumer;

use Doctrine\DBAL\Schema\AbstractSchemaManager as Manager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class Setup extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        /**
         * Prepare
         */
        $Manager = $this->readData()->getSchemaManager();
        $BaseSchema = $Manager->createSchema();
        $UpgradeSchema = clone $BaseSchema;
        /**
         * Setup - Table (PK)
         */
        $tblConsumer = $this->setupTableConsumer( $UpgradeSchema );
        $tblConsumerTyp = $this->setupTableConsumerTyp( $UpgradeSchema );
        /**
         * Setup - Table (FK)
         */
        $this->setupTableConsumerTypeList( $UpgradeSchema, $Manager, $tblConsumer, $tblConsumerTyp );
        /**
         * Migration
         */
        $Statement = $BaseSchema->getMigrateToSql( $UpgradeSchema,
            $this->readData()->getConnection()->getDatabasePlatform()
        );
        /**
         * Upgrade
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
        return $this->getInstallProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    protected function setupTableConsumer( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblConsumer' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblConsumer' );
            if (!$this->dbTableHasColumn( 'tblConsumer', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblConsumer', 'Name' )) {
                $Table->addColumn( 'Name', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblConsumer', 'TableSuffix' )) {
                $Table->addColumn( 'TableSuffix', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblConsumer', 'DatabaseSuffix' )) {
                $Table->addColumn( 'DatabaseSuffix', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblConsumer', 'apiManagement_Address' )) {
                $Table->addColumn( 'apiManagement_Address', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblConsumer' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'Name', 'string' );
            $Table->addColumn( 'DatabaseSuffix', 'string' );
            $Table->addColumn( 'TableSuffix', 'string' );
            $Table->addColumn( 'apiManagement_Address', 'bigint' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    protected function setupTableConsumerTyp( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblConsumerTyp' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblConsumerTyp' );
            if (!$this->dbTableHasColumn( 'tblConsumerTyp', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblConsumerTyp', 'Name' )) {
                $Table->addColumn( 'Name', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblConsumerTyp' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema  $Schema
     * @param Manager $Manager
     * @param Table   $tblConsumer
     * @param Table   $tblConsumerTyp
     *
     * @throws SchemaException
     * @return Table
     */
    protected function setupTableConsumerTypeList(
        Schema &$Schema,
        Manager $Manager,
        Table $tblConsumer,
        Table $tblConsumerTyp
    ) {

        if ($this->dbHasTable( 'tblConsumerTypeList' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblConsumerTypeList' );
            if (!$this->dbTableHasColumn( 'tblConsumerTypeList', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblConsumerTypeList', 'tblConsumer' )) {
                $Table->addColumn( 'tblConsumer', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblConsumer, array( 'tblConsumer' ), array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblConsumerTypeList', 'tblConsumerTyp' )) {
                $Table->addColumn( 'tblConsumerTyp', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblConsumerTyp, array( 'tblConsumerTyp' ), array( 'Id' ) );
                }
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblConsumerTypeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'tblConsumer', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumer, array( 'tblConsumer' ), array( 'Id' ) );
            }
            $Table->addColumn( 'tblConsumerTyp', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumerTyp, array( 'tblConsumerTyp' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

}
