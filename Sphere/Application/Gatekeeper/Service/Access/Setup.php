<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;
use KREDA\Sphere\Application\Service;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class Setup extends Service
{

    /** @var EntityManager $EntityManager */
    protected static $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    private static $SchemaManager = null;
    /** @var null|Schema $Schema */
    private static $Schema = null;

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Table
         */
        $Schema = clone $this->loadSchema();
        $tblAccessRight = $this->setTableAccessRight( $Schema );
        $tblAccessPrivilege = $this->setTableAccessPrivilege( $Schema );
        $tblAccessRole = $this->setTableAccessRole( $Schema );
        $this->setTableAccessPrivilegeRoleList( $Schema, $tblAccessPrivilege, $tblAccessRole );
        $this->setTableAccessRightPrivilegeList( $Schema, $tblAccessRight, $tblAccessPrivilege );
        /**
         * Migration
         */
        $Statement = $this->loadSchema()->getMigrateToSql( $Schema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                if (!$Simulate) {
                    $this->writeData()->prepareStatement( $Query )->executeWrite();
                }
            }
        }
        /**
         * View
         */
        if (!$this->dbHasView( 'viewAccess' )) {
            $viewAccess = $this->writeData()->getQueryBuilder()
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
                ->getSQL();
            $this->addInstallProtocol( 'viewAccess: '.$viewAccess );
            $this->loadSchemaManager()->createView( new View( 'viewAccess', $viewAccess ) );
        }
        /**
         * Protocol
         */
        return $this->getInstallProtocol();
    }

    /**
     * @return Schema|null
     */
    private function loadSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$Schema) {
            self::$Schema = $this->loadSchemaManager()->createSchema();
        }
        return self::$Schema;
    }

    /**
     * @return AbstractSchemaManager|null
     */
    private function loadSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$SchemaManager) {
            self::$SchemaManager = $this->writeData()->getSchemaManager();
        }
        return self::$SchemaManager;
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
        if (!$this->dbHasTable( 'tblAccessRight' )) {
            $Table = $Schema->createTable( 'tblAccessRight' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRight' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccessRight', 'Route' )) {
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
        if (!$this->dbHasTable( 'tblAccessPrivilege' )) {
            $Table = $Schema->createTable( 'tblAccessPrivilege' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessPrivilege' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccessPrivilege', 'Name' )) {
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
        if (!$this->dbHasTable( 'tblAccessRole' )) {
            $Table = $Schema->createTable( 'tblAccessRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRole' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccessRole', 'Name' )) {
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
        if (!$this->dbHasTable( 'tblAccessPrivilegeRoleList' )) {
            $Table = $Schema->createTable( 'tblAccessPrivilegeRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessPrivilegeRoleList' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->loadSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessRole' )) {
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($this->loadSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
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
        if (!$this->dbHasTable( 'tblAccessRightPrivilegeList' )) {
            $Table = $Schema->createTable( 'tblAccessRightPrivilegeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccessRightPrivilegeList' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'tblAccessRight' )) {
            $Table->addColumn( 'tblAccessRight', 'bigint' );
            if ($this->loadSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->loadSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @return EntityManager
     * @throws ORMException
     */
    protected function loadEntityManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === self::$EntityManager) {
            self::$EntityManager = EntityManager::create(
                $this->readData()->getConnection(),
                ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
            );
        }
        return self::$EntityManager;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRight()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->loadSchema()->getTable( 'tblAccessRight' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilege()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->loadSchema()->getTable( 'tblAccessPrivilege' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRole()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->loadSchema()->getTable( 'tblAccessRole' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRightPrivilegeList()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->loadSchema()->getTable( 'tblAccessRightPrivilegeList' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilegeRoleList()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->loadSchema()->getTable( 'tblAccessPrivilegeRoleList' );
    }
}
