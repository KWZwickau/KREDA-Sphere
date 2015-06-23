<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Balance
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
        $tblBalance = $this->setTableBalance( $Schema );
        $this->setTablePayment( $Schema, $tblBalance );
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
    private function setTableBalance( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBalance');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBalance', 'serviceBilling_Banking' ))
        {
            $Table->addColumn( 'serviceBilling_Banking', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblBalance', 'serviceBilling_Invoice' ))
        {
            $Table->addColumn( 'serviceBilling_Invoice', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblBalance', 'ExportDate' ))
        {
            $Table->addColumn( 'ExportDate', 'date', array( 'notnull' => false ) );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblBalance
     *
     * @return Table
     */
    private function setTablePayment( Schema &$Schema, Table $tblBalance )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblPayment');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblPayment', 'Value' ))
        {
            $Table->addColumn( 'Value', 'decimal' , array( 'precision' => 14 , 'scale' => 4) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPayment', 'Date' ))
        {
            $Table->addColumn( 'Date', 'date' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblBalance );

        return $Table;
    }
}
