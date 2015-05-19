<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Commodity
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
        $tblCommodity = $this->setTableCommodity( $Schema );
        $tblItem = $this->setTableItem( $Schema );
        $this->setTableCommodityItem( $Schema, $tblCommodity, $tblItem);

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
     */
    private function setTableCommodity( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCommodity' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        return $Table;
    }

    private function setTableItem ( Schema &$Schema)
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblItem' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'Price' )) {
            $Table->addColumn( 'Price', 'decimal' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblCommodity
     * @param Table  $tblItem
     *
     * @return Table
     */
    private function setTableCommodityItem( Schema &$Schema, Table $tblCommodity, Table $tblItem )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCommodityItem' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodityItem', 'Quantity' )) {
            $Table->addColumn( 'Quantity', 'decimal' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblCommodity );
        $this->schemaTableAddForeignKey( $Table, $tblItem );

        return $Table;
    }
}