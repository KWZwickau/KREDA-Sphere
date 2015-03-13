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
        $tblPersonType = $this->setTablePersonType( $Schema );
        $tblPersonGender = $this->setTablePersonGender( $Schema );
        $tblPersonSalutation = $this->setTablePersonSalutation( $Schema );
        $tblPerson = $this->setTablePerson( $Schema, $tblPersonType, $tblPersonGender, $tblPersonSalutation );
        $tblPersonRelationshipType = $this->setTablePersonRelationshipType( $Schema );
        $this->setTablePersonRelationshipList( $Schema, $tblPersonRelationshipType, $tblPerson );
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
    private function setTablePersonType( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblPersonType' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        $Table->addUniqueIndex( array( 'Name' ) );
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTablePersonGender( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblPersonGender' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonGender', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        $Table->addUniqueIndex( array( 'Name' ) );
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTablePersonSalutation( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblPersonSalutation' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonSalutation', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        $Table->addUniqueIndex( array( 'Name' ) );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblPersonType
     * @param Table  $tblPersonGender
     * @param Table  $tblPersonSalutation
     *
     * @return Table
     */
    private function setTablePerson(
        Schema &$Schema,
        Table $tblPersonType,
        Table $tblPersonGender,
        Table $tblPersonSalutation
    ) {

        $Table = $this->schemaTableCreate( $Schema, 'tblPerson' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'FirstName' )) {
            $Table->addColumn( 'FirstName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'MiddleName' )) {
            $Table->addColumn( 'MiddleName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'LastName' )) {
            $Table->addColumn( 'LastName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Birthday' )) {
            $Table->addColumn( 'Birthday', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Birthplace' )) {
            $Table->addColumn( 'Birthplace', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Nationality' )) {
            $Table->addColumn( 'Nationality', 'string' );
        }
        $this->schemaTableAddForeignKey( $Table, $tblPersonType );
        $this->schemaTableAddForeignKey( $Table, $tblPersonGender );
        $this->schemaTableAddForeignKey( $Table, $tblPersonSalutation );

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTablePersonRelationshipType( Schema &$Schema )
    {

        $Table = $this->schemaTableCreate( $Schema, 'tblPersonRelationshipType' );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonRelationshipType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        $Table->addUniqueIndex( array( 'Name' ) );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblPersonRelationshipType
     * @param Table  $tblPerson
     *
     * @return Table
     */
    private function setTablePersonRelationshipList(
        Schema &$Schema,
        Table $tblPersonRelationshipType,
        Table $tblPerson
    ) {

        $Table = $this->schemaTableCreate( $Schema, 'tblPersonRelationshipList' );
        $this->schemaTableAddForeignKey( $Table, $tblPersonRelationshipType );
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonRelationshipList', 'tblPersonA' )) {
            $Table->addColumn( 'tblPersonA', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblPerson, array( 'tblPersonA' ), array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonRelationshipList', 'tblPersonB' )) {
            $Table->addColumn( 'tblPersonB', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblPerson, array( 'tblPersonB' ), array( 'Id' ) );
            }
        }
        return $Table;
    }
}
