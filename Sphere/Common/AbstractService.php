<?php
namespace KREDA\Sphere\Common;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Database\Schema\EntityManager;
use KREDA\Sphere\IServiceInterface;

/**
 * Class AbstractService
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractService extends AbstractExtension implements IServiceInterface
{

    /** @var null|string $BaseRoute Client-Application Route */
    protected static $BaseRoute = null;
    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param null|string $BaseRoute Client-Application Route
     *
     * @return static Service Instance
     */
    final public static function getApi( $BaseRoute = null )
    {

        static::$BaseRoute = $BaseRoute;
        return new static;
    }

    /**
     * @param string $Application
     * @param string $Service
     * @param string $Consumer
     */
    final public function setDatabaseHandler( $Application, $Service = '', $Consumer = '' )
    {

        static::$DatabaseHandler = new Handler( new Identifier( $Application, $Service, $Consumer ) );
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        static::$DatabaseHandler->addProtocol( __CLASS__ );
        static::$DatabaseHandler->addProtocol( '<span class="text-danger">Missing Database-Schema Configuration!</span>' );
        return static::$DatabaseHandler->getProtocol( $Simulate );
    }

    /**
     * @return void
     */
    public function setupDatabaseContent()
    {

        static::$DatabaseHandler->addProtocol( __CLASS__ );
        static::$DatabaseHandler->addProtocol( '<span class="text-danger">Missing Database-Content Configuration!</span>' );
    }

    /**
     * @return string
     */
    final public function getConsumerSuffix()
    {

        if (false !== ( $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession() )) {
            return $tblConsumer->getDatabaseSuffix();
        } else {
            return 'EGE';
        }
    }

    /**
     * @param string $Route Service Route
     *
     * @return null|string Client-Application Route
     */
    final protected function getClientServiceRoute( $Route )
    {

        return $this->extensionRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
    }

    /**
     * @return EntityManager
     */
    final protected function getEntityManager()
    {

        return $this->getDatabaseHandler()->getEntityManager();
    }

    /**
     * @return Handler|null
     */
    final public function getDatabaseHandler()
    {

        return static::$DatabaseHandler;
    }

    /**
     * @param Schema $Schema
     * @param string $Name
     *
     * @return Table
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    final protected function schemaTableCreate( Schema &$Schema, $Name )
    {

        if (!$this->getDatabaseHandler()->hasTable( $Name )) {
            $Table = $Schema->createTable( $Name );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        return $Schema->getTable( $Name );
    }

    /**
     * @param Table $KeyTarget Foreign Key (Column: KeySource Name)
     * @param Table $KeySource Foreign Data (Column: Id)
     */
    final protected function schemaTableAddForeignKey( Table &$KeyTarget, Table $KeySource )
    {

        if (!$this->getDatabaseHandler()->hasColumn( $KeyTarget->getName(), $KeySource->getName() )) {
            $KeyTarget->addColumn( $KeySource->getName(), 'bigint' );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $KeyTarget->addForeignKeyConstraint( $KeySource, array( $KeySource->getName() ), array( 'Id' ) );
            }
        }
    }

    /**
     * @param Schema $Schema
     * @param bool   $Simulate
     */
    final protected function schemaMigration( Schema &$Schema, $Simulate = true )
    {

        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->getDatabaseHandler()->addProtocol( $Query );
                if (!$Simulate) {
                    $this->getDatabaseHandler()->setStatement( $Query );
                }
            }
        }
    }
}
