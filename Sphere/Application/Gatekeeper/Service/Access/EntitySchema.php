<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
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
        $tblAccessRight = $this->setTableAccessRight( $Schema );
        $tblAccessPrivilege = $this->setTableAccessPrivilege( $Schema );
        $tblAccess = $this->setTableAccess( $Schema );
        $this->setTableAccessPrivilegeList( $Schema, $tblAccessPrivilege, $tblAccess );
        $this->setTableAccessRightList( $Schema, $tblAccessRight, $tblAccessPrivilege );
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
    private function setTableAccessRight( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccessRight' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRight', 'Route' )) {
            $Table->addColumn( 'Route', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccessPrivilege( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccessPrivilege' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessPrivilege', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccess( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccess' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccess', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccessPrivilege
     * @param Table  $tblAccess
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccessPrivilegeList(
        Schema &$Schema,
        Table $tblAccessPrivilege,
        Table $tblAccess
    ) {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccessPrivilegeList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblAccessPrivilege );
        $this->schemaTableAddForeignKey( $Table, $tblAccess );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccessRight
     * @param Table  $tblAccessPrivilege
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccessRightList(
        Schema &$Schema,
        Table $tblAccessRight,
        Table $tblAccessPrivilege
    ) {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccessRightList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblAccessRight );
        $this->schemaTableAddForeignKey( $Table, $tblAccessPrivilege );
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRight()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessRight' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilege()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessPrivilege' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccess()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccess' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRightList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessRightList' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilegeList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessPrivilegeList' );
    }
}
