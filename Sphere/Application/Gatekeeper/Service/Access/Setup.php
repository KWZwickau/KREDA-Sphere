<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
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
    protected $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    private $SchemaManager = null;
    /** @var null|Schema $Schema */
    private $Schema = null;

    /**
     * @throws ORMException
     */
    function __construct()
    {

        $this->SchemaManager = $this->writeData()->getSchemaManager();
        $this->Schema = $this->SchemaManager->createSchema();
        $this->EntityManager = EntityManager::create(
            $this->readData()->getConnection(),
            ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) )
        );
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        /**
         * Setup
         */
        $Schema = clone $this->Schema;
        $tblAccessRight = $this->setTableAccessRight( $Schema );
        $tblAccessPrivilege = $this->setTableAccessPrivilege( $Schema );
        $tblAccessRole = $this->setTableAccessRole( $Schema );
        $this->setTableAccessPrivilegeRoleList( $Schema, $tblAccessPrivilege, $tblAccessRole );
        $this->setTableAccessRightPrivilegeList( $Schema, $tblAccessRight, $tblAccessPrivilege );
        /**
         * Migration
         */
        $Statement = $this->Schema->getMigrateToSql( $Schema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        /**
         * Execute
         */
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                if (!$Simulate) {
                    $this->writeData()->prepareStatement( $Query )->executeWrite();
                }
            }
        }
        return $this->getInstallProtocol();
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
            if ($this->SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessRole' )) {
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($this->SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
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
            if ($this->SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'tblAccessPrivilege' )) {
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($this->SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
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

        return $this->Schema->getTable( 'tblAccessRight' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilege()
    {

        return $this->Schema->getTable( 'tblAccessPrivilege' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRole()
    {

        return $this->Schema->getTable( 'tblAccessRole' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessRightPrivilegeList()
    {

        return $this->Schema->getTable( 'tblAccessRightPrivilegeList' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccessPrivilegeRoleList()
    {

        return $this->Schema->getTable( 'tblAccessPrivilegeRoleList' );
    }
}
