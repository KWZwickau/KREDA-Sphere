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

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblConsumer' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'TableSuffix' )) {
            $Table->addColumn( 'TableSuffix', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'DatabaseSuffix' )) {
            $Table->addColumn( 'DatabaseSuffix', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'serviceManagement_Address' )) {
            $Table->addColumn( 'serviceManagement_Address', 'bigint', array( 'notnull' => false ) );
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

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblConsumerTyp' );
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

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblConsumerTypList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblConsumer );
        $this->schemaTableAddForeignKey( $Table, $tblConsumerTyp );
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumer()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumer' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTyp()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTyp' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTypList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTypList' );
    }

}
