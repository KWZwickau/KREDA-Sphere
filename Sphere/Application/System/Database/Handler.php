<?php
namespace KREDA\Sphere\Application\System\Database;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager as SchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\QueryBuilder;
use KREDA\Sphere\Application\System\Database\Connector\Connector;
use KREDA\Sphere\Application\System\Database\Handler\EntityManager;
use KREDA\Sphere\Application\System\Database\Handler\Model;
use KREDA\Sphere\Common\AbstractAddOn;

/**
 * Class Handler
 *
 * @package KREDA\Sphere\Application\System\Database\Handler
 */
class Handler extends AbstractAddOn
{

    /** @var Identifier $Identifier */
    private $Identifier = null;
    /** @var Model $Model */
    private $Model = null;
    /** @var null $DatabasePlatform */
    private $DatabasePlatform = null;

    /**
     * Config:
     *
     * [{Service}{Consumer}]
     * Driver = {'DriverClass'}
     * Host = {'IpAddress'}
     * Port = {}
     * Username = {'UserName'}
     * Password = {'Password'}
     * Database = {'DatabaseName'}
     *
     * @param Identifier $Identifier
     *
     * @throws \Exception
     */
    final public function __construct( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->Identifier = $Identifier;
        if (!Connector::getInstance()->hasConnection( $Identifier )) {
            $Config = __DIR__.'/Config/'.$Identifier->getApplication().'.ini';
            if (false !== ( $Config = realpath( $Config ) )) {
                $Setting = parse_ini_file( $Config, true );
                if (isset( $Setting[$Identifier->getService().':'.$Identifier->getConsumer()] )) {

                    $Setting = $Setting[$Identifier->getService().':'.$Identifier->getConsumer()];

                    $Driver = __NAMESPACE__.'\\Driver\\'.$Setting['Driver'];
                    Connector::getInstance()->addConnection(
                        $Identifier, new $Driver,
                        $Setting['Username'], $Setting['Password'], $Setting['Database'],
                        $Setting['Host'], empty( $Setting['Port'] ) ? null : $Setting['Port']
                    );
                } else {
                    throw new \Exception( 'Missing Setting for ['.$Identifier->getService().':'.$Identifier->getConsumer().']' );
                }
            } else {
                throw new \Exception( 'Missing Database-Configuration for '.$Identifier->getApplication().'['.$Identifier->getService().':'.$Identifier->getConsumer().']' );
            }
        }
        $this->Model = new Model( $Identifier );
    }

    /**
     * @param string $Item
     */
    final public function addProtocol( $Item )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->Model->addProtocol( $Item );
    }


    /**
     * @param bool $Simulate
     *
     * @return string
     */
    final public function getProtocol( $Simulate = false )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getProtocol( $Simulate );
    }

    /**
     * @return AbstractPlatform
     */
    final public function getDatabasePlatform()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );
        if (null === $this->DatabasePlatform) {
            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );
            $this->DatabasePlatform = $this->Model->getConnection()->getConnection()->getDatabasePlatform();
        }
        return $this->DatabasePlatform;
    }

    /**
     * @param $Statement
     *
     * @return int The number of affected rows
     */
    final public function setStatement( $Statement )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getConnection()->prepareStatement( $Statement )->executeWrite();
    }

    /**
     * @param $Statement
     *
     * @return array
     */
    final public function getStatement( $Statement )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getConnection()->prepareStatement( $Statement )->executeRead();
    }

    /**
     * @return Schema
     */
    final public function getSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getSchema();
    }

    /**
     * @return SchemaManager
     */
    final public function getSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getSchemaManager();
    }

    /**
     * @return QueryBuilder
     */
    final public function getQueryBuilder()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->getConnection()->getQueryBuilder();
    }

    /**
     * @return EntityManager
     */
    final public function getEntityManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Path = __DIR__
            .'/../../'.$this->Identifier->getApplication()
            .'/Service/'.$this->Identifier->getService()
            .'/Entity';
        $Namespace = '\\KREDA\\Sphere\\Application\\'.$this->Identifier->getApplication()
            .'\\Service\\'.$this->Identifier->getService()
            .'\\Entity\\';

        return new EntityManager( $this->Model->getEntityManager( $Path ), $Namespace );
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    final public function hasTable( $TableName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->hasTable( $TableName );
    }

    /**
     * @param string $TableName
     * @param string $ColumnName
     *
     * @return bool
     */
    final public function hasColumn( $TableName, $ColumnName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->hasColumn( $TableName, $ColumnName );
    }

    /**
     * @param string $ViewName
     *
     * @return bool
     */
    final public function hasView( $ViewName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Model->hasView( $ViewName );
    }
}
