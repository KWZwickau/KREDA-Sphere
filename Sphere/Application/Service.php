<?php
namespace KREDA\Sphere\Application;

use KREDA\Sphere\IServiceInterface;
use MOC\V\Component\Database\Component\IBridgeInterface;
use MOC\V\Component\Database\Database;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Service
 *
 * @package KREDA\Sphere
 */
abstract class Service implements IServiceInterface
{

    /** @var IBridgeInterface $DatabaseMaster */
    protected static $DatabaseMaster = null;
    /** @var IBridgeInterface[] $DatabaseSlave */
    protected static $DatabaseSlave = array();
    /** @var null|string $BaseRoute Client-Application Route */
    protected static $BaseRoute = null;

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

    abstract protected function setupDataStructure();

    /**
     * @param string $Route Service Route
     *
     * @return null|string Client-Application Route
     */
    final protected function useRoute( $Route )
    {

        return HttpKernel::getRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
    }

    /**
     * @return IBridgeInterface
     */
    final protected function readData()
    {

        $SlaveCount = count( static::$DatabaseSlave );
        if ($SlaveCount == 0) {
            return static::$DatabaseMaster;
        } else {
            return static::$DatabaseSlave[rand( 0, $SlaveCount - 1 )];
        }
    }

    /**
     * Database Write Access
     *
     * @param string $Username
     * @param string $Password
     * @param string $Database
     * @param int    $Driver
     * @param string $Host
     * @param null   $Port
     *
     * @return $this
     */
    final protected function registerDatabaseMaster( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        static::$DatabaseMaster = Database::getDatabase( $Username, $Password, $Database, $Driver, $Host, $Port );
        return $this;
    }

    /**
     * Database Read Access
     *
     * @param string $Username
     * @param string $Password
     * @param string $Database
     * @param int    $Driver
     * @param string $Host
     * @param null   $Port
     *
     * @return $this
     */
    final protected function registerDatabaseSlave( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        static::$DatabaseSlave[] = Database::getDatabase( $Username, $Password, $Database, $Driver, $Host, $Port );
        return $this;
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    protected function dbHasTable( $TableName )
    {

        $SchemaManager = $this->writeData()->getSchemaManager();
        $NameList = $SchemaManager->listTableNames();
        $NameList = array_map( 'strtolower', $NameList );
        return in_array( strtolower( $TableName ), $NameList );
    }

    /**
     * @return IBridgeInterface
     */
    final protected function writeData()
    {

        return static::$DatabaseMaster;
    }

    /**
     * @param string $TableName
     * @param string $ColumnName
     *
     * @return bool
     */
    protected function dbTableHasColumn( $TableName, $ColumnName )
    {

        $SchemaManager = $this->writeData()->getSchemaManager();
        $NameList = array_keys( $SchemaManager->listTableColumns( $TableName ) );
        $NameList = array_map( 'strtolower', $NameList );
        return in_array( strtolower( $ColumnName ), $NameList );
    }
}
