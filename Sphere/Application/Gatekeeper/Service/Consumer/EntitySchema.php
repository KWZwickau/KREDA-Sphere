<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Consumer
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
        $tblConsumer = $this->setTableConsumer( $Schema );
        $tblConsumerTyp = $this->setTableConsumerTyp( $Schema );
        $this->setTableConsumerTypList( $Schema, $tblConsumer, $tblConsumerTyp );
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
         * View
         */
        if (!$this->getDatabaseHandler()->hasView( 'viewConsumer' )) {
            $viewConsumer = $this->getDatabaseHandler()->getQueryBuilder()
                ->select( array(
                    'Co.Id AS tblConsumer',
                    'Co.Name AS ConsumerName',
                    'Co.DatabaseSuffix AS ConsumerDatabaseSuffix',
                    'Co.TableSuffix AS ConsumerTableSuffix',
                    'Co.serviceManagement_Address AS serviceManagement_Address',
                    'CoTy.Id AS tblConsumerTypList',
                    'Ty.Id AS tblConsumerTyp',
                    'Ty.Name AS TypName',
                ) )
                ->from( 'tblConsumer', 'Co' )
                ->innerJoin( 'Co', 'tblConsumerTypList', 'CoTy', 'CoTy.tblConsumer = Co.Id' )
                ->innerJoin( 'CoTy', 'tblConsumerTyp', 'Ty', 'CoTy.tblConsumerTyp = Ty.Id' )
                ->getSQL();
            $this->getDatabaseHandler()->addProtocol( 'viewConsumer: '.$viewConsumer );
            if (!$Simulate) {
                $this->getDatabaseHandler()->getSchemaManager()->createView( new View( 'viewConsumer',
                    $viewConsumer ) );
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
    private function setTableConsumer( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumer' )) {
            $Table = $Schema->createTable( 'tblConsumer' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumer' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'TableSuffix' )) {
            $Table->addColumn( 'TableSuffix', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'DatabaseSuffix' )) {
            $Table->addColumn( 'DatabaseSuffix', 'string', array( 'notnull' => false ) );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumer', 'serviceManagement_Address' )) {
            $Table->addColumn( 'serviceManagement_Address', 'bigint', array( 'notnull' => false ) );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableConsumerTyp( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumerTyp' )) {
            $Table = $Schema->createTable( 'tblConsumerTyp' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumerTyp' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTyp', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblConsumer
     * @param Table  $tblConsumerTyp
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableConsumerTypList(
        Schema &$Schema,
        Table $tblConsumer,
        Table $tblConsumerTyp
    ) {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblConsumerTypList' )) {
            $Table = $Schema->createTable( 'tblConsumerTypList' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblConsumerTypList' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTypList', 'tblConsumer' )) {
            $Table->addColumn( 'tblConsumer', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumer, array( 'tblConsumer' ),
                    array( 'Id' ) );
            }
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblConsumerTypList', 'tblConsumerTyp' )) {
            $Table->addColumn( 'tblConsumerTyp', 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblConsumerTyp, array( 'tblConsumerTyp' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumer()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumer' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTyp()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTyp' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableConsumerTypList()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblConsumerTypList' );
    }

}
