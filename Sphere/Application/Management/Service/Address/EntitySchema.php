<?php
namespace KREDA\Sphere\Application\Management\Service\Address;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Address
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
        $tblAddressCity = $this->setTableAddressCity( $Schema );
        $tblAddressState = $this->setTableAddressState( $Schema );
        $this->setTableAddress( $Schema, $tblAddressCity, $tblAddressState );
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
    private function setTableAddressCity( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAddressCity' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddressCity', 'Code' )) {
            $Table->addColumn( 'Code', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddressCity', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddressCity', 'District' )) {
            $Table->addColumn( 'District', 'string', array( 'notnull' => false ) );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAddressState( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAddressState' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddressState', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Name' ) )) {
            $Table->addUniqueIndex( array( 'Name' ) );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAddressCity
     * @param Table  $tblAddressState
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAddress( Schema &$Schema, Table $tblAddressCity, Table $tblAddressState )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAddress' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddress', 'StreetName' )) {
            $Table->addColumn( 'StreetName', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddress', 'StreetNumber' )) {
            $Table->addColumn( 'StreetNumber', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddress', 'PostOfficeBox' )) {
            $Table->addColumn( 'PostOfficeBox', 'string', array( 'notnull' => false ) );
        }
        $this->schemaTableAddForeignKey( $Table, $tblAddressCity );
        $this->schemaTableAddForeignKey( $Table, $tblAddressState );
        return $Table;
    }
}
