<?php
namespace KREDA\Sphere\Application\Management\Service\Student;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Student
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
        $tblChildRank = $this->setTableChildRank( $Schema );
        $this->setTableStudent( $Schema, $tblChildRank );
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
    private function setTableChildRank( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblChildRank' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblChildRank', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblChildRank', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblChildRank
     *
     * @return Table
     */
    private function setTableStudent( Schema &$Schema, Table $tblChildRank )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblStudent' );
        $this->schemaTableAddForeignKey( $Table, $tblChildRank );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblStudent', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblStudent', 'serviceManagement_Course' )) {
            $Table->addColumn( 'serviceManagement_Course', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblStudent', 'StudentNumber' )) {
            $Table->addColumn( 'StudentNumber', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'StudentNumber' ) )) {
            $Table->addUniqueIndex( array( 'StudentNumber' ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblStudent', 'TransferFromDate' )) {
            $Table->addColumn( 'TransferFromDate', 'date', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblStudent', 'TransferToDate' )) {
            $Table->addColumn( 'TransferToDate', 'date', array( 'notnull' => false ) );
        }
        return $Table;
    }
}
