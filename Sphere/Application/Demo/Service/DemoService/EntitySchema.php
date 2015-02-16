<?php
namespace KREDA\Sphere\Application\Demo\Service\DemoService;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Demo\Service\DemoService
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
        $this->setTableDemoCompleter( $Schema );
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
    private function setTableDemoCompleter( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblDemoCompleter' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDemoCompleter', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblDemoCompleter', 'Description' )) {
            $Table->addColumn( 'Description', 'string', array( 'notnull' => false ) );
        }
        return $Table;
    }
}
