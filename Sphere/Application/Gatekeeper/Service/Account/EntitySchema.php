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
        $tblAccountType = $this->setTableAccountType( $Schema );
        $tblAccount = $this->setTableAccount( $Schema,
            Gatekeeper::serviceToken()->getTableToken(),
            Gatekeeper::serviceConsumer()->getTableConsumer(),
            $tblAccountType,
            $tblAccountRole
        );
        $this->setTableAccountSession( $Schema,
            $tblAccount
        );
        $this->setTableAccountAccessList( $Schema,
            $tblAccountRole,
            Gatekeeper::serviceAccess()->getTableAccess()
        );
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
    private function setTableAccountRole( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccountRole' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountRole', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Name' ) )) {
            $Table->addUniqueIndex( array( 'Name' ) );
        }
        return $Table;
    }


    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableAccountType( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccountType' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Name' ) )) {
            $Table->addUniqueIndex( array( 'Name' ) );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblToken
     * @param Table  $tblConsumer
     * @param Table  $tblAccountType
     * @param Table  $tblAccountRole
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableAccount(
        Schema &$Schema,
        Table $tblToken,
        Table $tblConsumer,
        Table $tblAccountType,
        Table $tblAccountRole
    ) {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccount' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Username' )) {
            $Table->addColumn( 'Username', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Username' ) )) {
            $Table->addUniqueIndex( array( 'Username' ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Password' )) {
            $Table->addColumn( 'Password', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Username', 'Password' ) )) {
            $Table->addIndex( array( 'Username', 'Password' ) );
        }
        $this->schemaTableAddForeignKey( $Table, $tblAccountType );
        $this->schemaTableAddForeignKey( $Table, $tblAccountRole );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblAccountSession' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountSession', 'Session' )) {
            $Table->addColumn( 'Session', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasIndex( $Table, array( 'Session' ) )) {
            $Table->addIndex( array( 'Session' ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountSession', 'Timeout' )) {
            $Table->addColumn( 'Timeout', 'integer' );
        }
        $this->schemaTableAddForeignKey( $Table, $tblAccount );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblAccountAccessList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblAccountRole );
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

}
