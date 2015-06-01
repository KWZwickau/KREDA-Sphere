<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Invoice
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
        $tblInvoice = $this->setTableInvoice( $Schema );
        $tblInvoiceItem = $this->setTableInvoiceItem( $Schema, $tblInvoice);
        $this->setTableInvoiceAccount( $Schema, $tblInvoiceItem );

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
    private function setTableInvoice( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblInvoice');
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'IsConfirmed' ))
        {
            $Table->addColumn( 'IsConfirmed', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'IsPaid' ))
        {
            $Table->addColumn( 'IsPaid', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'Number' ))
        {
            $Table->addColumn( 'Number', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'IsVoid' ))
        {
            $Table->addColumn( 'IsVoid', 'boolean' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'InvoiceDate' ))
        {
            $Table->addColumn( 'InvoiceDate', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'PaymentDate' ))
        {
            $Table->addColumn( 'PaymentDate', 'date' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'Discount' ))
        {
            $Table->addColumn( 'Discount', 'decimal' , array( 'precision' => 14 , 'scale' => 4) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'DebtorFirstName' ))
        {
            $Table->addColumn( 'DebtorFirstName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'DebtorLastName' ))
        {
            $Table->addColumn( 'DebtorLastName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'DebtorSalutation' ))
        {
            $Table->addColumn( 'DebtorSalutation', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'DebtorNumber' ))
        {
            $Table->addColumn( 'DebtorNumber', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'serviceManagement_Address' ))
        {
            $Table->addColumn( 'serviceManagement_Address', 'bigint', array( 'notnull' => false ));
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoice', 'serviceManagement_Person' ))
        {
            $Table->addColumn( 'serviceManagement_Person', 'bigint', array( 'notnull' => false ));
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblInvoice
     * @return Table
     */
    private function setTableInvoiceItem( Schema &$Schema, Table $tblInvoice )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblInvoiceItem');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'CommodityDescription' ))
        {
            $Table->addColumn( 'CommodityDescription', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'CommodityName' ))
        {
            $Table->addColumn( 'CommodityName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'ItemDescription' ))
        {
            $Table->addColumn( 'ItemDescription', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'ItemName' ))
        {
            $Table->addColumn( 'ItemName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'ItemPrice' ))
        {
            $Table->addColumn( 'ItemPrice', 'decimal' , array( 'precision' => 14 , 'scale' => 4) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceItem', 'ItemQuantity' ))
        {
            $Table->addColumn( 'ItemQuantity', 'decimal' , array( 'precision' => 14 , 'scale' => 4) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblInvoice );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblInvoiceItem
     * @return Table
     */
    private function setTableInvoiceAccount( Schema &$Schema, Table $tblInvoiceItem )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblInvoiceAccount');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblInvoiceAccount', 'serviceBilling_Account' ))
        {
            $Table->addColumn( 'serviceBilling_Account', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblInvoiceItem );

        return $Table;
    }
}
