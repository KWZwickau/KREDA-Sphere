<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;

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

        $tblPaymentType = $this->setTablePaymentType ( $Schema );
        $tblDebtor = $this->setTableDebtor( $Schema, $tblPaymentType );
        $this->setTableDebtorCommodity( $Schema, $tblDebtor );
        $this->setTableReference( $Schema, $tblDebtor );

        /**
         * Migration & Protocol
         */
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        $this->schemaMigration( $Schema, $Simulate );
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     * @param Table $tblPaymentType
     *
     * @return Table tblDebtorCommodity
     */
    private function setTableDebtor( Schema &$Schema, Table $tblPaymentType )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblDebtor' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','DebtorNumber' )){
            $Table->addColumn( 'DebtorNumber', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','LeadTimeFirst' )){
            $Table->addColumn( 'LeadTimeFirst', 'integer' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','LeadTimeFollow' )){
            $Table->addColumn( 'LeadTimeFollow', 'integer' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','BankName' )){
            $Table->addColumn( 'BankName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','IBAN' )){
            $Table->addColumn( 'IBAN', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','BIC' )){
            $Table->addColumn( 'BIC', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','Owner' )){
            $Table->addColumn( 'Owner', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','CashSign' )){
            $Table->addColumn( 'CashSign', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','Description' )){
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','ServiceManagementPerson' )){
            $Table->addColumn( 'ServiceManagementPerson', 'bigint', array('notnull' => false) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblPaymentType );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblDebtor
     *
     * @return Table
     */
    private function setTableDebtorCommodity( Schema &$Schema, Table $tblDebtor )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblDebtorCommodity');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtorCommodity', 'serviceBilling_Commodity')){
            $Table->addColumn( 'serviceBilling_Commodity', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblDebtor );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblDebtor
     *
     * @return Table
     */
    private function setTableReference( Schema &$Schema, Table $tblDebtor )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblReference');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblReference', 'Reference')){
            $Table->addColumn( 'Reference', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblReference', 'isVoid')){
            $Table->addColumn( 'isVoid', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblReference', 'ReferenceDate')){
            $Table->addColumn( 'ReferenceDate', 'date', array('notnull' => false) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblReference', 'tblCommodity')){
            $Table->addColumn( 'tblCommodity', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblDebtor );
        return $Table;
    }

    private function setTablePaymentType ( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblPaymentType');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblPaymentType', 'Name')){
            $Table->addColumn( 'Name', 'string' );
        }

        return $Table;
    }
}