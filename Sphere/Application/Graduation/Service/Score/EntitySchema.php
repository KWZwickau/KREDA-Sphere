<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Score
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
        $this->setTableScoreRule( $Schema );
        $this->setTableScoreGroup( $Schema );
        $this->setTableScoreCondition( $Schema );
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
    private function setTableScoreRule( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreRule' )) {
            $Table = $Schema->createTable( 'tblScoreRule' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreRule' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreRule', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreRule', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreGroup( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreGroup' )) {
            $Table = $Schema->createTable( 'tblScoreGroup' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreGroup' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroup', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroup', 'Round' )) {
            $Table->addColumn( 'Round', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroup', 'Multiplier' )) {
            $Table->addColumn( 'Multiplier', 'float' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreCondition( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreCondition' )) {
            $Table = $Schema->createTable( 'tblScoreCondition' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreCondition' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreCondition', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreCondition', 'Round' )) {
            $Table->addColumn( 'Round', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreCondition', 'Priority' )) {
            $Table->addColumn( 'Priority', 'integer' );
        }

        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableScoreRule()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblScoreRule' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableScoreGroup()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblScoreGroup' );
    }
}
