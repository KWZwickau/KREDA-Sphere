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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Table
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        $tblAccessRight = $this->setTableAccessRight( $Schema );
        $tblAccessPrivilege = $this->setTableAccessPrivilege( $Schema );
        $tblAccessRole = $this->setTableAccessRole( $Schema );
        $this->setTableAccessPrivilegeRoleList( $Schema, $tblAccessPrivilege, $tblAccessRole );
        $this->setTableAccessRightPrivilegeList( $Schema, $tblAccessRight, $tblAccessPrivilege );
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
                    'Ro.Id AS tblAccessRole',
                    'Ro.Name AS RoleName',
                    'PrRo.Id AS tblAccessPrivilegeRoleList',
                    'Pr.Id AS tblAccessPrivilege',
                    'Pr.Name AS PrivilegeName',
                    'RiPr.Id AS tblAccessRightPrivilegeList',
                    'Ri.Id AS tblAccessRight',
                    'Ri.Route AS RightRoute',
                ) )
                ->from( 'tblAccessRole', 'Ro' )
                ->innerJoin( 'Ro', 'tblAccessPrivilegeRoleList', 'PrRo', 'PrRo.tblAccessRole = Ro.Id' )
                ->innerJoin( 'PrRo', 'tblAccessPrivilege', 'Pr', 'PrRo.tblAccessPrivilege = Pr.Id' )
                ->innerJoin( 'Pr', 'tblAccessRightPrivilegeList', 'RiPr', 'RiPr.tblAccessPrivilege = Pr.Id' )
                ->innerJoin( 'RiPr', 'tblAccessRight', 'Ri', 'RiPr.tblAccessRight = Ri.Id' )
                ->getDQL();
            $this->getDatabaseHandler()->addProtocol( 'viewAccess: '.$viewAccess );
            $this->getDatabaseHandler()->getSchemaManager()->createView( new View( 'viewAccess', $viewAccess ) );
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

        $this->getDebugger()->addMethodCall( __METHOD__ );

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

        $this->getDebugger()->addMethodCall( __METHOD__ );

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
    private function setTableAccessRole( Schema &$Schema )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessRole' )) {
            $Table = $Schema->createTable( 'tblAccessRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRole' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRole', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccessPrivilege
     * @param Table  $tblAccessRole
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccessPrivilegeRoleList(
        Schema &$Schema,
        Table $tblAccessPrivilege,
        Table $tblAccessRole
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessPrivilegeRoleList' )) {
            $Table = $Schema->createTable( 'tblAccessPrivilegeRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessPrivilegeRoleList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessRole' )) {
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
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
    private function setTableAccessRightPrivilegeList(
        Schema &$Schema,
        Table $tblAccessRight,
        Table $tblAccessPrivilege
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccessRightPrivilegeList' )) {
            $Table = $Schema->createTable( 'tblAccessRightPrivilegeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRightPrivilegeList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRightPrivilegeList', 'tblAccessRight' )) {
            $Table->addColumn( 'tblAccessRight', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccessRightPrivilegeList', 'tblAccessPrivilege' )) {
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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessRight' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilege()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessPrivilege' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRole()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessRole' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRightPrivilegeList()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessRightPrivilegeList' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilegeRoleList()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccessPrivilegeRoleList' );
    }
}
