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
        $tblContact = $this->setTableContact( $Schema );
        $this->setTablePhone( $Schema, $tblContact );
        $this->setTableMail( $Schema, $tblContact );
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
     */
    private function setTableContact( Schema &$Schema)
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblContact' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblContact', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblContact', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblContact
     *
     * @return Table
     */
    private function setTablePhone( Schema &$Schema, Table $tblContact )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblPhone' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'Number' )) {
            $Table->addColumn( 'Number', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'Rank' )) {
            $Table->addColumn( 'Rank', 'int' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'serviceManagement_Company' )) {
            $Table->addColumn( 'serviceManagement_Company', 'bigint', array( 'notnull' => false ) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblContact );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblContact
     *
     * @return Table
     */
    private function setTableMail( Schema &$Schema, Table $tblContact )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblMail' );

        if (!$this->getDatabaseHandler()->hasColumn( 'tblMail', 'Address' )) {
            $Table->addColumn( 'Address', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblMail', 'Description' )) {
            $Table->addColumn( 'Description', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPhone', 'Rank' )) {
            $Table->addColumn( 'Rank', 'int' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblMail', 'serviceManagement_Person' )) {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblMail', 'serviceManagement_Company' )) {
            $Table->addColumn( 'serviceManagement_Company', 'bigint', array( 'notnull' => false ) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblContact );

        return $Table;
    }

}
