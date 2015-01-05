<?php
namespace KREDA\Sphere\Application\Graduation\Service\Grade;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Grade
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
        $this->setTableGradeType( $Schema );
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
    private function setTableGradeType( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblGradeType' )) {
            $Table = $Schema->createTable( 'tblGradeType' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblGradeType' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGradeType', 'Acronym' )) {
            $Table->addColumn( 'Acronym', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGradeType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }

        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableGradeType()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblGradeType' );
    }
}
