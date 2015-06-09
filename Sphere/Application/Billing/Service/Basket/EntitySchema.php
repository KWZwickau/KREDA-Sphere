<?php
namespace KREDA\Sphere\Application\Billing\Service\Basket;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Billing\Service\Basket
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
        $tblBasket = $this->setTableBasket( $Schema );
        $this->setTableBasketPerson( $Schema, $tblBasket );
        $this->setTableBasketItem( $Schema, $tblBasket );

        $tblBasketCommodity = $this->setTableBasketCommodity( $Schema, $tblBasket );
        $this->setTableBasketCommodityDebtor( $Schema, $tblBasketCommodity );
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
    private function setTableBasket( Schema &$Schema )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBasket');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasket', 'CreateDate' ))
        {
            $Table->addColumn( 'CreateDate', 'datetime' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblBasket
     *
     * @return Table
     */
    private function setTableBasketPerson( Schema &$Schema, Table $tblBasket )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBasketPerson');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketPerson', 'serviceManagement_Person' ))
        {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblBasket );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblBasket
     *
     * @return Table
     */
    private function setTableBasketItem( Schema &$Schema, Table $tblBasket )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBasketItem');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketItem', 'serviceBilling_CommodityItem' ))
        {
            $Table->addColumn( 'serviceBilling_CommodityItem', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketItem', 'Price' )) {
            $Table->addColumn( 'Price', 'decimal', array( 'precision' => 14 , 'scale' => 4) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketItem', 'Quantity' )) {
            $Table->addColumn( 'Quantity', 'decimal', array( 'precision' => 14 , 'scale' => 4) );
        }

        $this->schemaTableAddForeignKey( $Table, $tblBasket );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblBasket
     *
     * @return Table
     */
    private function setTableBasketCommodity( Schema &$Schema, Table $tblBasket )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBasketCommodity');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketCommodity', 'serviceManagement_Person' ))
        {
            $Table->addColumn( 'serviceManagement_Person', 'bigint' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketCommodity', 'serviceBilling_Commodity' ))
        {
            $Table->addColumn( 'serviceBilling_Commodity', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblBasket );

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblBasketCommodity
     *
     * @return Table
     */
    private function setTableBasketCommodityDebtor( Schema &$Schema, Table $tblBasketCommodity )
    {
        $Table = $this->schemaTableCreate( $Schema, 'tblBasketCommodityDebtor');

        if (!$this->getDatabaseHandler()->hasColumn( 'tblBasketCommodityDebtor', 'serviceBilling_Debtor' ))
        {
            $Table->addColumn( 'serviceBilling_Debtor', 'bigint' );
        }

        $this->schemaTableAddForeignKey( $Table, $tblBasketCommodity );

        return $Table;
    }
}
