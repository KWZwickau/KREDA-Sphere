<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use Doctrine\DBAL\Schema\AbstractSchemaManager as Manager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Service;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
abstract class Setup extends Service
{

    /**
     * @return string
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function setupDataStructure()
    {

        /**
         * Prepare
         */
        $Manager = $this->writeData()->getSchemaManager();
        $BaseSchema = $Manager->createSchema();
        $UpgradeSchema = clone $BaseSchema;
        /**
         * Setup - Table (PK)
         */
        $tblYubiKey = $this->setupTableYubiKey( $UpgradeSchema );
        $tblAccountRole = $this->setupTableAccountRole( $UpgradeSchema );
        $tblAccessRight = $this->setupTableAccessRight( $UpgradeSchema );
        $tblAccessPrivilege = $this->setupTableAccessPrivilege( $UpgradeSchema );
        $tblAccessRole = $this->setupTableAccessRole( $UpgradeSchema );
        /**
         * Setup - Table (FK)
         */
        $this->setupTableAccount( $UpgradeSchema, $Manager, $tblYubiKey );
        $this->setupTableAccountRoleList( $UpgradeSchema, $Manager, $tblAccountRole, $tblAccessRole );
        $this->setupTableAccessPrivilegeRoleList( $UpgradeSchema, $Manager, $tblAccessPrivilege, $tblAccessRole );
        $this->setupTableAccessRightPrivilegeList( $UpgradeSchema, $Manager, $tblAccessRight, $tblAccessPrivilege );
        /**
         * Migration
         */
        $Statement = $BaseSchema->getMigrateToSql( $UpgradeSchema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        /**
         * Upgrade
         */
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                $this->writeData()->prepareStatement( $Query )->executeWrite();
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
    protected function setupTableYubiKey( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblYubiKey' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblYubiKey' );
            if (!$this->dbTableHasColumn( 'tblYubiKey', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblYubiKey', 'YubiKey' )) {
                $Table->addColumn( 'YubiKey', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblYubiKey' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'YubiKey', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    protected function setupTableAccountRole( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblAccountRole' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccountRole' );
            if (!$this->dbTableHasColumn( 'tblAccountRole', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccountRole', 'Name' )) {
                $Table->addColumn( 'Name', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccountRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
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
    protected function setupTableAccessRight( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblAccessRight' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccessRight' );
            if (!$this->dbTableHasColumn( 'tblAccessRight', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccessRight', 'Class' )) {
                $Table->addColumn( 'Class', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblAccessRight', 'Method' )) {
                $Table->addColumn( 'Method', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccessRight' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'Class', 'string' );
            $Table->addColumn( 'Method', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    protected function setupTableAccessPrivilege( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblAccessPrivilege' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccessPrivilege' );
            if (!$this->dbTableHasColumn( 'tblAccessPrivilege', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccessPrivilege', 'Name' )) {
                $Table->addColumn( 'Name', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccessPrivilege' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
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
    protected function setupTableAccessRole( Schema &$Schema )
    {

        if ($this->dbHasTable( 'tblAccessRole' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccessRole' );
            if (!$this->dbTableHasColumn( 'tblAccessRole', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccessRole', 'Name' )) {
                $Table->addColumn( 'Name', 'string' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccessRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema  $Schema
     * @param Manager $Manager
     * @param Table   $tblYubiKey
     *
     * @return Table
     * @throws SchemaException
     */
    protected function setupTableAccount( Schema &$Schema, Manager $Manager, Table $tblYubiKey )
    {

        if ($this->dbHasTable( 'tblAccount' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccount' );
            if (!$this->dbTableHasColumn( 'tblAccount', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'Username' )) {
                $Table->addColumn( 'Username', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'Password' )) {
                $Table->addColumn( 'Password', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'tblYubiKey' )) {
                $Table->addColumn( 'tblYubiKey', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblYubiKey, array( 'tblYubiKey' ), array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'apiHumanResources_Person' )) {
                $Table->addColumn( 'apiHumanResources_Person', 'bigint' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'apiSystem_Consumer' )) {
                $Table->addColumn( 'apiSystem_Consumer', 'bigint' );
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccount' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'Username', 'string' );
            $Table->addColumn( 'Password', 'string' );
            $Table->addColumn( 'tblYubiKey', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblYubiKey, array( 'tblYubiKey' ), array( 'Id' ) );
            }
            $Table->addColumn( 'apiHumanResources_Person', 'bigint' );
            $Table->addColumn( 'apiSystem_Consumer', 'bigint' );
        }
        return $Table;
    }

    /**
     * @param Schema  $Schema
     * @param Manager $Manager
     * @param Table   $tblAccountRole
     * @param Table   $tblAccessRole
     *
     * @throws SchemaException
     * @return Table
     */
    protected function setupTableAccountRoleList(
        Schema &$Schema,
        Manager $Manager,
        Table $tblAccountRole,
        Table $tblAccessRole
    ) {

        if ($this->dbHasTable( 'tblAccountRoleList' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccountRoleList' );
            if (!$this->dbTableHasColumn( 'tblAccountRoleList', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccountRoleList', 'tblAccountRole' )) {
                $Table->addColumn( 'tblAccountRole', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblAccountRoleList', 'tblAccessRole' )) {
                $Table->addColumn( 'tblAccessRole', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
                }
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccountRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'tblAccountRole', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
            }
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @param Schema  $Schema
     * @param Manager $Manager
     * @param Table   $tblAccessPrivilege
     * @param Table   $tblAccessRole
     *
     * @throws SchemaException
     * @return Table
     */
    protected function setupTableAccessPrivilegeRoleList(
        Schema &$Schema,
        Manager $Manager,
        Table $tblAccessPrivilege,
        Table $tblAccessRole
    ) {

        if ($this->dbHasTable( 'tblAccessPrivilegeRoleList' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccessPrivilegeRoleList' );
            if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessPrivilege' )) {
                $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                        array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblAccessPrivilegeRoleList', 'tblAccessRole' )) {
                $Table->addColumn( 'tblAccessRole', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
                }
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccessPrivilegeRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ), array( 'Id' ) );
            }
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @param Schema  $Schema
     * @param Manager $Manager
     * @param Table   $tblAccessRight
     * @param Table   $tblAccessPrivilege
     *
     * @throws SchemaException
     * @return Table
     */
    protected function setupTableAccessRightPrivilegeList(
        Schema &$Schema,
        Manager $Manager,
        Table $tblAccessRight,
        Table $tblAccessPrivilege
    ) {

        if ($this->dbHasTable( 'tblAccessRightPrivilegeList' )) {
            // Upgrade
            $Table = $Schema->getTable( 'tblAccessRightPrivilegeList' );
            if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'Id' )) {
                $Column = $Table->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $Table->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'tblAccessRight' )) {
                $Table->addColumn( 'tblAccessRight', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblAccessRightPrivilegeList', 'tblAccessPrivilege' )) {
                $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
                if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ),
                        array( 'Id' ) );
                }
            }
        } else {
            // Install
            $Table = $Schema->createTable( 'tblAccessRightPrivilegeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
            $Table->addColumn( 'tblAccessPrivilege', 'bigint' );
            $Table->addColumn( 'tblAccessRight', 'bigint' );
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRight, array( 'tblAccessRight' ), array( 'Id' ) );
            }
            if ($Manager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessPrivilege, array( 'tblAccessPrivilege' ), array( 'Id' ) );
            }
        }
        return $Table;
    }
}
