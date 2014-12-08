<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Consumer
 */
abstract class EntitySchema extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Table
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        $tblConsumer = $this->setTableConsumer( $Schema );
        $tblConsumerTyp = $this->setTableConsumerTyp( $Schema );
        $this->setTableConsumerTypList( $Schema, $tblConsumer, $tblConsumerTyp );
        /**
         * Migration
         */
        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->getDatabaseHandler()->addProtocol( $Query );
                if (!$Simulate) {
                    $this->getDatabaseHandler()->setStatement( $Query );
                }
            }
        }
        /**
         * View
         */

        /**
         * Protocol
         */
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableConsumer( Schema &$Schema )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumer' )) {
            $Table = $Schema->createTable( 'tblConsumer' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumer' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'Identifier' )) {
            $Table->addColumn( 'Identifier', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'TableSuffix' )) {
            $Table->addColumn( 'TableSuffix', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'DatabaseSuffix' )) {
            $Table->addColumn( 'DatabaseSuffix', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'apiManagement_Address' )) {
            $Table->addColumn( 'apiManagement_Address', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableConsumerTyp( Schema &$Schema )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumerTyp' )) {
            $Table = $Schema->createTable( 'tblConsumerTyp' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumerTyp' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTyp', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblConsumer
     * @param Table  $tblConsumerTyp
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableConsumerTypList(
        Schema &$Schema,
        Table $tblConsumer,
        Table $tblConsumerTyp
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumerTypList' )) {
            $Table = $Schema->createTable( 'tblConsumerTypList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumerTypList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTypList', 'tblConsumer' )) {
            $Table->addColumn( 'tblConsumer', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumer, array( 'tblConsumer' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTypList', 'tblConsumerTyp' )) {
            $Table->addColumn( 'tblConsumerTyp', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumerTyp, array( 'tblConsumerTyp' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumer()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumer' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTyp()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTyp' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTypList()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTypList' );
    }

}
