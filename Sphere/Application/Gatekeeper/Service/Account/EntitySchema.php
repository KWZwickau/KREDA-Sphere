<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
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
         * Table
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        $tblAccountRole = $this->setTableAccountRole( $Schema );
        $tblAccountTyp = $this->setTableAccountTyp( $Schema );
        $tblAccount = $this->setTableAccount( $Schema,
            Gatekeeper::serviceToken()->schemaTableToken(),
            Gatekeeper::serviceConsumer()->schemaTableConsumer(),
            $tblAccountTyp,
            $tblAccountRole
        );
        $this->setTableAccountSession( $Schema,
            $tblAccount
        );
        $this->setTableAccountAccessList( $Schema,
            $tblAccountRole,
            Gatekeeper::serviceAccess()->schemaTableAccess()
        );
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
     * @param Table  $tblConsumer
     * @param Table  $tblAccountTyp
     * @param Table  $tblAccountRole
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccount(
        Schema &$Schema,
        Table $tblToken,
        Table $tblConsumer,
        Table $tblAccountTyp,
        Table $tblAccountRole
    ) {

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
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'tblAccountTyp' )) {
            $Table->addColumn( 'tblAccountTyp', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountTyp, array( 'tblAccountTyp' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'tblAccountRole' )) {
            $Table->addColumn( 'tblAccountRole', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'serviceGatekeeper_Token' )) {
            $Table->addColumn( 'serviceGatekeeper_Token', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblToken, array( 'serviceGatekeeper_Token' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'serviceGatekeeper_Consumer' )) {
            $Table->addColumn( 'serviceGatekeeper_Consumer', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumer, array( 'serviceGatekeeper_Consumer' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ) );
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
     * @param Table  $tblAccess
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccountAccessList(
        Schema &$Schema,
        Table $tblAccountRole,
        Table $tblAccess
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblAccountAccessList' )) {
            $Table = $Schema->createTable( 'tblAccountAccessList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        $Table = $Schema->getTable( 'tblAccountAccessList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountAccessList', 'tblAccountRole' )) {
            $Table->addColumn( 'tblAccountRole', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccountRole, array( 'tblAccountRole' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountAccessList', 'serviceGatekeeper_Access' )) {
            $Table->addColumn( 'serviceGatekeeper_Access', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblAccess, array( 'serviceGatekeeper_Access' ), array( 'Id' ) );
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
    protected function getTableAccountAccessList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblAccountAccessList' );
    }
}
