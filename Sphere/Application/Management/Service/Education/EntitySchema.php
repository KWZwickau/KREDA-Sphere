<?php
namespace KREDA\Sphere\Application\Management\Service\Education;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Education
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
        $tblTerm = $this->setTableTerm( $Schema );
        $tblSubject = $this->setTableSubject( $Schema );
        $tblLevel = $this->setTableLevel( $Schema );
        $tblGroup = $this->setTableGroup( $Schema );
        $tblCategory = $this->setTableCategory( $Schema );
        $this->setTableSubjectCategory( $Schema, $tblSubject, $tblCategory );
        $tblSubjectGroup = $this->setTableSubjectGroup( $Schema, $tblTerm, $tblLevel, $tblSubject, $tblGroup );
        $this->setTableSubjectGroupTeacher( $Schema, $tblSubjectGroup );
        $this->setTableSubjectGroupStudent( $Schema, $tblSubjectGroup );
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
    private function setTableTerm( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblTerm' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblTerm', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblTerm', 'FirstDateFrom' )) {
            $Table->addColumn( 'FirstDateFrom', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblTerm', 'FirstDateTo' )) {
            $Table->addColumn( 'FirstDateTo', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblTerm', 'SecondDateFrom' )) {
            $Table->addColumn( 'SecondDateFrom', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblTerm', 'SecondDateTo' )) {
            $Table->addColumn( 'SecondDateTo', 'date' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableSubject( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblSubject' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubject', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubject', 'Acronym' )) {
            $Table->addColumn( 'Acronym', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableLevel( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblLevel' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblLevel', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblLevel', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableGroup( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblGroup' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'Description' )) {
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
    private function setTableCategory( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblCategory' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblCategory', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblSubject
     * @param Table  $tblCategory
     *
     * @return Table
     */
    private function setTableSubjectCategory( Schema &$Schema, Table $tblSubject, Table $tblCategory )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblSubjectCategory' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblSubject );
        $this->schemaTableAddForeignKey( $Table, $tblCategory );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblTerm
     * @param Table  $tblLevel
     * @param Table  $tblSubject
     * @param Table  $tblGroup
     *
     * @return Table
     */
    private function setTableSubjectGroup(
        Schema &$Schema,
        Table $tblTerm,
        Table $tblLevel,
        Table $tblSubject,
        Table $tblGroup
    ) {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblSubjectGroup' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblTerm );
        $this->schemaTableAddForeignKey( $Table, $tblLevel );
        $this->schemaTableAddForeignKey( $Table, $tblSubject );
        $this->schemaTableAddForeignKey( $Table, $tblGroup );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubjectGroup', 'serviceGraduation_Dimension' )) {
            $Table->addColumn( 'serviceGraduation_Dimension', 'bigint', array( 'notnull' => false ) );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblSubjectGroup
     *
     * @return Table
     */
    private function setTableSubjectGroupTeacher( Schema &$Schema, Table $tblSubjectGroup )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblSubjectGroupTeacher' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblSubjectGroup );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubjectGroupTeacher', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblSubjectGroup
     *
     * @return Table
     */
    private function setTableSubjectGroupStudent( Schema &$Schema, Table $tblSubjectGroup )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblSubjectGroupStudent' );
        /**
         * Upgrade
         */
        $this->schemaTableAddForeignKey( $Table, $tblSubjectGroup );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubjectGroupStudent', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }
        return $Table;
    }
}
