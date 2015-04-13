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
        $tblAccountType = $this->setTableAccountType( $Schema );
        $this->setTableAccount( $Schema, $tblAccountType );
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
    private function setTableAccount( Schema &$Schema, Table $tblAccountType )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblAccount' );
        /**
         * Upgrade
         */
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
        return $Table;
    }
}
