<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
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
        $this->setTablePerson( $Schema );
        /**
         * Migration
         */
        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->getDatabaseHandler()->addProtocol( $Query );
                if (!$Simulate) {
                    $this->getDatabaseHandler()->setStatement( $Query );
                }
            }
        }
        /**
         * View
         */

        /**
         * Protocol
         */
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTablePerson( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblPerson' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Salutation' )) {
            $Table->addColumn( 'Salutation', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'FirstName' )) {
            $Table->addColumn( 'FirstName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'MiddleName' )) {
            $Table->addColumn( 'MiddleName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'LastName' )) {
            $Table->addColumn( 'LastName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Gender' )) {
            $Table->addColumn( 'Gender', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Birthday' )) {
            $Table->addColumn( 'Birthday', 'string' );
        }

        return $Table;
    }
}
