<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
 */
abstract class Setup extends \KREDA\Sphere\Application\Setup
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Table
         */
        $Schema = clone $this->loadSchema();
        $this->setTableToken( $Schema );
        /**
         * Migration
         */
        $Statement = $this->loadSchema()->getMigrateToSql( $Schema,
            $this->writeData()->getConnection()->getDatabasePlatform()
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
            $this->loadSchemaManager()->createView( new View( 'viewToken', $viewToken ) );
        }
        /**
         * Protocol
         */
        return $this->getInstallProtocol();
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

        return $this->loadSchema()->getTable( 'tblToken' );
    }
}
