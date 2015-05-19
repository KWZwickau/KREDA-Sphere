<?php
namespace KREDA\Sphere\Application\Billing\Service\Account;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
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
        $tblAccountKeyType = $this->setTableAccountKeyType( $Schema );
        $tblAccountType = $this->setTableAccountType( $Schema );
        $tblAccountKey = $this->setTableAccountKey( $Schema, $tblAccountKeyType );
        $this->setTableAccount( $Schema, $tblAccountType, $tblAccountKey );
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
     */
    private function setTableAccountType( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblAccountType' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountType', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblAccountType
     *
     * @return Table
     */
    private function setTableAccount( Schema &$Schema, Table $tblAccountType, Table $tblAccountKey )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblAccount' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Number' )) {
            $Table->addColumn( 'Number', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccount', 'Value' )) {
            $Table->addColumn( 'Value', 'float' );
        }
        $this->schemaTableAddForeignKey( $Table, $tblAccountType );
        $this->schemaTableAddForeignKey( $Table, $tblAccountKey );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table $tblAccountKeyType
     *
     * @return Table
     */
    private function setTableAccountKeyType( Schema &$Schema)
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblAccountKeyType' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKeyType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKeyType', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table $tblAccountKey
     *
     * @return Table
     */
    private function setTableAccountKey( Schema &$Schema, Table $tblAccountKeyType )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblAccountKey' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKey', 'ValidFrom' )){
            $Table->addColumn( 'ValidFrom', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKey', 'Value' )){
            $Table->addColumn( 'Value', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKey', 'ValidTo' )){
            $Table->addColumn( 'ValidTo', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKey', 'Description' )){
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblAccountKey', 'Code' )){
            $Table->addColumn( 'Code', 'integer' );
        }
        $this->schemaTableAddForeignKey( $Table, $tblAccountKeyType );
        return $Table;
    }

}