<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
abstract class Setup extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        /**
         * Setup
         */
        $Schema = clone $this->getSchema();
        $tblAccountRole = $this->setTableAccountRole( $Schema );
        $tblAccount = $this->setTableAccount( $Schema, Token::getApi()->schemaTableToken() );
        $this->setTableAccountSession( $Schema, $tblAccount );
        $this->setTableAccountRoleList( $Schema, $tblAccountRole, Access::getApi()->schemaTableAccessRole() );
        /**
         * Migration
         */
        $Statement = $this->getSchema()->getMigrateToSql( $Schema,
            $this->readData()->getConnection()->getDatabasePlatform()
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
        return $this->getInstallProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccountRole( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblAccountRole' )) {
            $Table = $Schema->createTable( 'tblAccountRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountRole' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccountRole', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblToken
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccount( Schema &$Schema, Table $tblToken )
    {

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblAccount' )) {
            $Table = $Schema->createTable( 'tblAccount' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccount' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccount', 'Username' )) {
            $Table->addColumn( 'Username', 'string' );
        }
        if (!$this->dbTableHasColumn( 'tblAccount', 'Password' )) {
            $Table->addColumn( 'Password', 'string' );
        }
        if (!$this->dbTableHasColumn( 'tblAccount', 'tblToken' )) {
            $Table->addColumn( 'tblToken', 'bigint', array( 'notnull' => false ) );
            if ($this->getSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblToken, array( 'tblToken' ), array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccount', 'apiHumanResources_Person' )) {
            $Table->addColumn( 'apiHumanResources_Person', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->dbTableHasColumn( 'tblAccount', 'apiSystem_Consumer' )) {
            $Table->addColumn( 'apiSystem_Consumer', 'bigint', array( 'notnull' => false ) );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccount
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccountSession( Schema &$Schema, Table $tblAccount )
    {

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblAccountSession' )) {
            $Table = $Schema->createTable( 'tblAccountSession' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountSession' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccountSession', 'Session' )) {
            $Table->addColumn( 'Session', 'string' );
        }
        if (!$this->dbTableHasColumn( 'tblAccountSession', 'Timeout' )) {
            $Table->addColumn( 'Timeout', 'integer' );
        }
        if (!$this->dbTableHasColumn( 'tblAccountSession', 'tblAccount' )) {
            $Table->addColumn( 'tblAccount', 'bigint' );
            if ($this->getSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccount, array( 'tblAccount' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccountRole
     * @param Table  $tblAccessRole
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccountRoleList(
        Schema &$Schema,
        Table $tblAccountRole,
        Table $tblAccessRole
    ) {

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblAccountRoleList' )) {
            $Table = $Schema->createTable( 'tblAccountRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountRoleList' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblAccountRoleList', 'tblAccountRole' )) {
            $Table->addColumn( 'tblAccountRole', 'bigint' );
            if ($this->getSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
            }
        }
        if (!$this->dbTableHasColumn( 'tblAccountRoleList', 'tblAccessRole' )) {
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($this->getSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccessRole, array( 'tblAccessRole' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccount()
    {

        return $this->getSchema()->getTable( 'tblAccount' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountRole()
    {

        return $this->getSchema()->getTable( 'tblAccountRole' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountSession()
    {

        return $this->getSchema()->getTable( 'tblAccountSession' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountRoleList()
    {

        return $this->getSchema()->getTable( 'tblAccountRoleList' );
    }
}
