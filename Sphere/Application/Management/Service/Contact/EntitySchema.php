<?php
namespace KREDA\Sphere\Application\Management\Service\Contact;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Contact
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
        $this->setTableContact( $Schema );
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
    private function setTableContact( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblContact' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblContact', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ) );
        }
        return $Table;
    }

}
