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
    private function setTableAddressCity( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAddressCity' )) {
            $Table = $Schema->createTable( 'tblAddressCity' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblAddressCity' );
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAddressState' )) {
            $Table = $Schema->createTable( 'tblAddressState' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblAddressState' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddressState', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAddress' )) {
            $Table = $Schema->createTable( 'tblAddress' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblAddress' );
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
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddress', 'tblAddressCity' )) {
            $Table->addColumn( 'tblAddressCity', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAddressCity, array( 'tblAddressCity' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAddress', 'tblAddressState' )) {
            $Table->addColumn( 'tblAddressState', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAddressState, array( 'tblAddressState' ), array( 'Id' ) );
            }
        }
        return $Table;
    }
}
