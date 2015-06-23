<?php
namespace KREDA\Sphere\Application\Management\Service\TableView;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\TableView
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
        $tblView = $this->setTableView( $Schema );
        $this->setTableViewColumn( $Schema, $tblView );
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
    private function setTableView( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblView' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblView', 'TypeName' )) {
            $Table->addColumn( 'TypeName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblView', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblView
     *
     * @return Table
     */
    private function setTableViewColumn( Schema &$Schema, Table $tblView )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblViewColumn' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblViewColumn', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblViewColumn', 'DataType' )) {
            $Table->addColumn( 'DataType', 'string' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblView );

        return $Table;
    }
}