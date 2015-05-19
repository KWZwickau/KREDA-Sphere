<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Commodity
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
        $tblCommodity = $this->setTableCommodity( $Schema );
        //$this->setTableAccount( $Schema, $tblAccountType );
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
    private function setTableCommodity( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblCommodity' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCommodity', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        return $Table;
    }
}