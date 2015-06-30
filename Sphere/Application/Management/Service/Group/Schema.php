<?php
namespace KREDA\Sphere\Application\Management\Service\Group;

use Doctrine\DBAL\Schema\Schema as DBALSchema;
use Doctrine\DBAL\Schema\Table as DBALTable;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Management\Service\Group
 */
abstract class Schema extends AbstractService
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
        $tblGroup = $this->setTableGroup( $Schema );
        $this->setTablePersonList( $Schema, $tblGroup );
        $this->setTableCompanyList( $Schema, $tblGroup );
        /**
         * Migration & Protocol
         */
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        $this->schemaMigration( $Schema, $Simulate );
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param DBALSchema $Schema
     *
     * @return DBALTable
     */
    private function setTableGroup( DBALSchema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblGroup' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'Description' )) {
            $Table->addColumn( 'Description', 'string', array( 'notnull' => false ) );
        }

        return $Table;
    }

    /**
     * @param DBALSchema $Schema
     * @param DBALTable  $tblGroup
     *
     * @return DBALTable
     */
    private function setTablePersonList( DBALSchema &$Schema, DBALTable $tblGroup )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblPersonList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblGroup );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonList', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }
        return $Table;
    }

    /**
     * @param DBALSchema $Schema
     * @param DBALTable  $tblGroup
     *
     * @return DBALTable
     */
    private function setTableCompanyList( DBALSchema &$Schema, DBALTable $tblGroup )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblCompanyList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblGroup );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCompanyList', 'serviceManagement_Company' )) {
            $Table->addColumn( 'serviceManagement_Company', 'bigint' );
        }
        return $Table;
    }
}
