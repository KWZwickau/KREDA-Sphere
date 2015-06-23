<?php
namespace KREDA\Sphere\Application\Management\Service\Company;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Company
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
        $tblCompany = $this->setTableCompany( $Schema );
        $this->setTableCompanyAddress( $Schema, $tblCompany );
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
    private function setTableCompany( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCompany');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblCompany', 'Name' ))
        {
            $Table->addColumn( 'Name', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblCompany
     *
     * @return Table
     */
    private function setTableCompanyAddress( Schema &$Schema, Table $tblCompany )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblCompanyAddress');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblCompanyAddress', 'serviceManagement_Address' ))
        {
            $Table->addColumn( 'serviceManagement_Address', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblCompany );

        return $Table;
    }
}
