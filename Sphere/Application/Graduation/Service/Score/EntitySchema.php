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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreRule' );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreCondition' );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreGroup' );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreRuleConditionList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $TblScoreRule );
        $this->schemaTableAddForeignKey( $Table, $TblScoreCondition );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreConditionGroupList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $TblScoreCondition );
        $this->schemaTableAddForeignKey( $Table, $TblScoreGroup );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreGroupGradeTypeList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $TblScoreGroup );
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
        $Table = $this->schemaTableCreate( $Schema, 'tblScoreConditionGradeTypeList' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $TblScoreCondition );
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
