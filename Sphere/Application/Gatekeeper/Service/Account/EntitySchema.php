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
         * Setup
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        $tblAccountRole = $this->setTableAccountRole( $Schema );
        $tblAccountTyp = $this->setTableAccountTyp( $Schema );
        $tblAccount = $this->setTableAccount( $Schema, Token::getApi()->schemaTableToken(), $tblAccountTyp );
        $this->setTableAccountSession( $Schema, $tblAccount );
        $this->setTableAccountRoleList( $Schema, $tblAccountRole, Access::getApi()->schemaTableAccessRole() );
        /**
         * Migration
         */
        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        /**
         * Execute
         */
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
    private function setTableAccountRole( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccountRole' )) {
            $Table = $Schema->createTable( 'tblAccountRole' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountRole' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountRole', 'Name' )) {
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
    private function setTableAccountTyp( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccountTyp' )) {
            $Table = $Schema->createTable( 'tblAccountTyp' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountTyp' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountTyp', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblToken
     * @param Table  $tblAccountTyp
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccount( Schema &$Schema, Table $tblToken, Table $tblAccountTyp )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccount' )) {
            $Table = $Schema->createTable( 'tblAccount' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccount' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Username' )) {
            $Table->addColumn( 'Username', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Password' )) {
            $Table->addColumn( 'Password', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'tblToken' )) {
            $Table->addColumn( 'tblToken', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblToken, array( 'tblToken' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'tblAccountTyp' )) {
            $Table->addColumn( 'tblAccountTyp', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountTyp, array( 'tblAccountTyp' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'apiHumanResources_Person' )) {
            $Table->addColumn( 'apiHumanResources_Person', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'apiSystem_Consumer' )) {
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccountSession' )) {
            $Table = $Schema->createTable( 'tblAccountSession' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountSession' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountSession', 'Session' )) {
            $Table->addColumn( 'Session', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountSession', 'Timeout' )) {
            $Table->addColumn( 'Timeout', 'integer' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountSession', 'tblAccount' )) {
            $Table->addColumn( 'tblAccount', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
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
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccountRoleList' )) {
            $Table = $Schema->createTable( 'tblAccountRoleList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountRoleList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountRoleList', 'tblAccountRole' )) {
            $Table->addColumn( 'tblAccountRole', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountRoleList', 'tblAccessRole' )) {
            $Table->addColumn( 'tblAccessRole', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
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

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccount' );
    }


    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountTyp()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccountTyp' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountRole()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccountRole' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountSession()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccountSession' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableAccountRoleList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccountRoleList' );
    }
}
