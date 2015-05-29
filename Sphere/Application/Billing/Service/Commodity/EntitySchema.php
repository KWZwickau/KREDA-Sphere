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
        $tblCommodityType = $this->setTableCommodityType( $Schema );
        $tblCommodity = $this->setTableCommodity( $Schema, $tblCommodityType);
        $tblItem = $this->setTableItem( $Schema );
        $this->setTableCommodityItem( $Schema, $tblCommodity, $tblItem);
        $this->setTableItemAccount( $Schema, $tblItem );
        $this->setTableItemCondition( $Schema, $tblItem );

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
    private function setTableCommodityType( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCommodityType');
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodityType', 'Name' ))
        {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblCommodityType
     *
     * @return Table
     */
    private function setTableCommodity( Schema &$Schema, Table $tblCommodityType)
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCommodity' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblCommodityType );

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
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
            $Table->addColumn( 'Price', 'decimal', array( 'precision' => 14 , 'scale' => 4) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'CostUnit' )) {
            $Table->addColumn( 'CostUnit', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'serviceManagement_Student_ChildRank' ))
        {
            $Table->addColumn( 'serviceManagement_Student_ChildRank', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblItem', 'serviceManagement_Course' ))
        {
            $Table->addColumn( 'serviceManagement_Course', 'bigint' );
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
            $Table->addColumn( 'Quantity', 'decimal' , array( 'precision' => 14 , 'scale' => 4) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblCommodity );
        $this->schemaTableAddForeignKey( $Table, $tblItem );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblItem
     *
     * @return Table
     */
    private function setTableItemAccount( Schema &$Schema, Table $tblItem)
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblItemAccount' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblItemAccount', 'serviceBilling_Account' )) {
            $Table->addColumn( 'serviceBilling_Account', 'bigint');
        }

        $this->schemaTableAddForeignKey( $Table, $tblItem );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblItem
     *
     * @return Table
     */
    private function setTableItemCondition( Schema &$Schema, Table $tblItem)
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblItemCondition' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblItemCondition', 'serviceManagement_Student' ))
        {
            $Table->addColumn( 'serviceManagement_Student', 'bigint');
        }

        $this->schemaTableAddForeignKey( $Table, $tblItem );

        return $Table;
    }
}