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
        $tblScoreRule = $this->setTableScoreRule( $Schema );
        $tblScoreCondition = $this->setTableScoreCondition( $Schema );
        $tblScoreGroup = $this->setTableScoreGroup( $Schema );
        $this->setTableScoreRuleConditionList( $Schema, $tblScoreRule, $tblScoreCondition );
        $this->setTableScoreConditionGroupList( $Schema, $tblScoreCondition, $tblScoreGroup );
        $this->setTableScoreGroupGradeTypeList( $Schema, $tblScoreGroup );
        $this->setTableScoreConditionGradeTypeList( $Schema, $tblScoreCondition );
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
            $Table->addColumn( 'Round', 'boolean', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreCondition', 'Priority' )) {
            $Table->addColumn( 'Priority', 'integer' );
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
            $Table->addColumn( 'Round', 'boolean', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroup', 'Multiplier' )) {
            $Table->addColumn( 'Multiplier', 'float' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $TblScoreRule
     * @param Table  $TblScoreCondition
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreRuleConditionList(
        Schema &$Schema,
        Table $TblScoreRule,
        Table $TblScoreCondition
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreRuleConditionList' )) {
            $Table = $Schema->createTable( 'tblScoreRuleConditionList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreRuleConditionList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreRuleConditionList', 'tblScoreRule' )) {
            $Table->addColumn( 'tblScoreRule', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreRule, array( 'tblScoreRule' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreRuleConditionList', 'tblScoreCondition' )) {
            $Table->addColumn( 'tblScoreCondition', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreCondition, array( 'tblScoreCondition' ), array( 'Id' ) );
            }
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $TblScoreCondition
     * @param Table  $TblScoreGroup
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreConditionGroupList(
        Schema &$Schema,
        Table $TblScoreCondition,
        Table $TblScoreGroup
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreConditionGroupList' )) {
            $Table = $Schema->createTable( 'tblScoreConditionGroupList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreConditionGroupList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreConditionGroupList', 'tblScoreCondition' )) {
            $Table->addColumn( 'tblScoreCondition', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreCondition, array( 'tblScoreCondition' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreConditionGroupList', 'tblScoreGroup' )) {
            $Table->addColumn( 'tblScoreGroup', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreGroup, array( 'tblScoreGroup' ), array( 'Id' ) );
            }
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $TblScoreGroup
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreGroupGradeTypeList(
        Schema &$Schema,
        Table $TblScoreGroup
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreGroupGradeTypeList' )) {
            $Table = $Schema->createTable( 'tblScoreGroupGradeTypeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreGroupGradeTypeList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroupGradeTypeList', 'tblScoreGroup' )) {
            $Table->addColumn( 'tblScoreGroup', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreGroup, array( 'tblScoreGroup' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroupGradeTypeList', 'serviceGraduation_Grade' )) {
            $Table->addColumn( 'serviceGraduation_Grade', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreGroupGradeTypeList', 'Multiplier' )) {
            $Table->addColumn( 'Multiplier', 'float' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $TblScoreCondition
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableScoreConditionGradeTypeList(
        Schema &$Schema,
        Table $TblScoreCondition
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblScoreConditionGradeTypeList' )) {
            $Table = $Schema->createTable( 'tblScoreConditionGradeTypeList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblScoreConditionGradeTypeList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreConditionGradeTypeList', 'tblScoreCondition' )) {
            $Table->addColumn( 'tblScoreCondition', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $TblScoreCondition, array( 'tblScoreCondition' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblScoreConditionGradeTypeList', 'serviceGraduation_Grade' )) {
            $Table->addColumn( 'serviceGraduation_Grade', 'bigint', array( 'notnull' => false ) );
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
