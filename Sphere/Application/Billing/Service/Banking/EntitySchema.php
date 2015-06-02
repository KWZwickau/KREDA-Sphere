<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;

use Doctrine\DBAL\Schema\Schema;
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

        $this->setTableDebtor( $Schema );
        $this->setTableDebtorCommodity( $Schema );


        /**
         * Migration & Protocol
         */
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        $this->schemaMigration( $Schema, $Simulate );
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     * @return Table tblDebtorCommodity
     */
    private function setTableDebtor( Schema &$Schema )
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
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtor','ServiceManagement_Person' )){
            $Table->addColumn( 'ServiceManagement_Person', 'bigint', array('notnull' => false) );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table tblDebtorCommodity
     */
    private function setTableDebtorCommodity( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblDebtorCommodity');
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtorCommodity', 'Commodity')){
            $Table->addColumn( 'Commodity', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDebtorCommodity', 'Debtor')){
            $Table->addColumn( 'Debtor', 'bigint' );
        }
        return $Table;
    }
}