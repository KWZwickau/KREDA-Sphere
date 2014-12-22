<?php
namespace KREDA\Sphere\Common\Database;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager as SchemaManager;
use Doctrine\DBAL\Schema\Schema;
use KREDA\Sphere\Common\Database\Connection\Connector;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Schema\EntityManager;
use KREDA\Sphere\Common\Database\Schema\Model;

/**
 * Class Handler
 *
 * @package KREDA\Sphere\Common\Database
 */
class Handler
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

        $this->Identifier = $Identifier;
        $Connector = new Connector();
        if (!$Connector->hasConnection( $Identifier )) {
            $Config = __DIR__.'/Config/'.$Identifier->getApplication().'.ini';
            if (false !== ( $Config = realpath( $Config ) )) {
                $Setting = parse_ini_file( $Config, true );
                if (isset( $Setting[$Identifier->getService().':'.$Identifier->getConsumer()] )) {

                    $Setting = $Setting[$Identifier->getService().':'.$Identifier->getConsumer()];

                    $Driver = __NAMESPACE__.'\\Driver\\Platform\\'.$Setting['Driver'];
                    $Connector->addConnection(
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
        $this->Model = new Model( $this->Identifier );
    }

    /**
     * @param string $Item
     */
    final public function addProtocol( $Item )
    {

        $this->Model->addProtocol( $Item );
    }


    /**
     * @param bool $Simulate
     *
     * @return string
     */
    final public function getProtocol( $Simulate = false )
    {

        return $this->Model->getProtocol( $Simulate );
    }

    /**
     * @return AbstractPlatform
     */
    final public function getDatabasePlatform()
    {

        if (null === $this->DatabasePlatform) {
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

        return $this->Model->getConnection()->prepareStatement( $Statement )->executeWrite();
    }

    /**
     * @param $Statement
     *
     * @return array
     */
    final public function getStatement( $Statement )
    {

        return $this->Model->getConnection()->prepareStatement( $Statement )->executeRead();
    }

    /**
     * @return Schema
     */
    final public function getSchema()
    {

        return $this->Model->getSchema();
    }

    /**
     * @return SchemaManager
     */
    final public function getSchemaManager()
    {

        return $this->Model->getSchemaManager();
    }

    /**
     * @return QueryBuilder
     */
    final public function getQueryBuilder()
    {

        return $this->Model->getConnection()->getQueryBuilder();
    }

    /**
     * @return EntityManager
     */
    final public function getEntityManager()
    {

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

        return $this->Model->hasColumn( $TableName, $ColumnName );
    }

    /**
     * @param string $ViewName
     *
     * @return bool
     */
    final public function hasView( $ViewName )
    {

        return $this->Model->hasView( $ViewName );
    }
}
