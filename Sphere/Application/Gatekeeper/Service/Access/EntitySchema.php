<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
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
         * View
         */
        if (!$this->getDatabaseHandler()->hasView( 'viewAccess' )) {
            $viewAccess = $this->getDatabaseHandler()->getQueryBuilder()
                ->select( array(
                    'Ro.Id AS tblAccess',
                    'Ro.Name AS RoleName',
                    'PrRo.Id AS tblAccessPrivilegeList',
                    'Pr.Id AS tblAccessPrivilege',
                    'Pr.Name AS PrivilegeName',
                    'RiPr.Id AS tblAccessRightList',
                    'Ri.Id AS tblAccessRight',
                    'Ri.Route AS RightRoute',
                ) )
                ->from( 'tblAccess', 'Ro' )
                ->innerJoin( 'Ro', 'tblAccessPrivilegeList', 'PrRo', 'PrRo.tblAccess = Ro.Id' )
                ->innerJoin( 'PrRo', 'tblAccessPrivilege', 'Pr', 'PrRo.tblAccessPrivilege = Pr.Id' )
                ->innerJoin( 'Pr', 'tblAccessRightList', 'RiPr', 'RiPr.tblAccessPrivilege = Pr.Id' )
                ->innerJoin( 'RiPr', 'tblAccessRight', 'Ri', 'RiPr.tblAccessRight = Ri.Id' )
                ->getSQL();
            $this->getDatabaseHandler()->addProtocol( 'viewAccess: '.$viewAccess );
            if (!$Simulate) {
                $this->getDatabaseHandler()->getSchemaManager()->createView( new View( 'viewAccess', $viewAccess ) );
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
    private function setTableAccessRight( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessRight' )) {
            $Table = $Schema->createTable( 'tblAccessRight' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRight' );
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessPrivilege' )) {
            $Table = $Schema->createTable( 'tblAccessPrivilege' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessPrivilege' );
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccess' )) {
            $Table = $Schema->createTable( 'tblAccess' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccess' );
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessPrivilegeList' )) {
            $Table = $Schema->createTable( 'tblAccessPrivilegeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessPrivilegeList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessPrivilegeList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessPrivilegeList', 'tblAccess' )) {
            $Table->addColumn( 'tblAccess', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccess, array( 'tblAccess' ), array( 'Id' ) );
            }
        }
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessRightList' )) {
            $Table = $Schema->createTable( 'tblAccessRightList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRightList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRightList', 'tblAccessRight' )) {
            $Table->addColumn( 'tblAccessRight', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRightList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
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
