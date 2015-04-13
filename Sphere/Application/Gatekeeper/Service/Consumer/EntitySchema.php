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
        $tblConsumerType = $this->setTableConsumerType( $Schema );
        $this->setTableConsumerTypeList( $Schema, $tblConsumer, $tblConsumerType );
        /**
         * Migration & Protocol
         */
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        $this->schemaMigration( $Schema, $Simulate );
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
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'TableSuffix' ) )) {
            $Table->addUniqueIndex( array( 'TableSuffix' ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'DatabaseSuffix' )) {
            $Table->addColumn( 'DatabaseSuffix', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'DatabaseSuffix' ) )) {
            $Table->addUniqueIndex( array( 'DatabaseSuffix' ) );
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
    private function setTableConsumerType( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblConsumerType' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Name' ) )) {
            $Table->addUniqueIndex( array( 'Name' ) );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblConsumer
     * @param Table  $tblConsumerType
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableConsumerTypeList(
        Schema &$Schema,
        Table $tblConsumer,
        Table $tblConsumerType
    ) {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblConsumerTypeList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblConsumer );
        $this->schemaTableAddForeignKey( $Table, $tblConsumerType );
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

}
