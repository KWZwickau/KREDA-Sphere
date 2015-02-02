<?php
namespace KREDA\Sphere\Application\System\Service\Protocol;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\System\Service\Protocol
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
        $this->setTableProtocol( $Schema );
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
    private function setTableProtocol( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblProtocol' );
        /**
         * Upgrade
         */
        // System
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'ProtocolDatabase' )) {
            $Table->addColumn( 'ProtocolDatabase', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'ProtocolTimestamp' )) {
            $Table->addColumn( 'ProtocolTimestamp', 'integer', array( 'notnull' => false ) );
        }
        // Editor
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'serviceGatekeeper_Account' )) {
            $Table->addColumn( 'serviceGatekeeper_Account', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'AccountUsername' )) {
            $Table->addColumn( 'AccountUsername', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'PersonFirstName' )) {
            $Table->addColumn( 'PersonFirstName', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'PersonLastName' )) {
            $Table->addColumn( 'PersonLastName', 'string', array( 'notnull' => false ) );
        }
        // Consumer
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'serviceGatekeeper_Consumer' )) {
            $Table->addColumn( 'serviceGatekeeper_Consumer', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'ConsumerName' )) {
            $Table->addColumn( 'ConsumerName', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'ConsumerSuffix' )) {
            $Table->addColumn( 'ConsumerSuffix', 'string', array( 'notnull' => false ) );
        }
        // Data
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'EntityFrom' )) {
            $Table->addColumn( 'EntityFrom', 'text', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblProtocol', 'EntityTo' )) {
            $Table->addColumn( 'EntityTo', 'text', array( 'notnull' => false ) );
        }

        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableProtocol()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblProtocol' );
    }
}
