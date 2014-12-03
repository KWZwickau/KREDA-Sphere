<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Table
         */
        $Schema = clone $this->getSchema();
        $this->setTableToken( $Schema );
        /**
         * Migration
         */
        $Statement = $this->getSchema()->getMigrateToSql( $Schema,
            $this->readData()->getConnection()->getDatabasePlatform()
        );
        $this->addInstallProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->addInstallProtocol( $Query );
                if (!$Simulate) {
                    $this->writeData()->prepareStatement( $Query )->executeWrite();
                }
            }
        }
        /**
         * View
         */
        if (!$this->dbHasView( 'viewToken' )) {
            $viewToken = $this->writeData()->getQueryBuilder()
                ->select( array(
                    'Id AS tblToken',
                    'Identifier AS TokenIdentifier',
                ) )
                ->from( 'tblToken' )
                ->getSQL();
            $this->addInstallProtocol( 'viewToken: '.$viewToken );
            $this->getSchemaManager()->createView( new View( 'viewToken', $viewToken ) );
        }
        /**
         * Protocol
         */
        return $this->getInstallProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableToken( Schema &$Schema )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Install
         */
        if (!$this->dbHasTable( 'tblToken' )) {
            $Table = $Schema->createTable( 'tblToken' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblToken' );
        /**
         * Upgrade
         */
        if (!$this->dbTableHasColumn( 'tblToken', 'Identifier' )) {
            $Table->addColumn( 'Identifier', 'string' );
        }

        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getSchema()->getTable( 'tblToken' );
    }
}
