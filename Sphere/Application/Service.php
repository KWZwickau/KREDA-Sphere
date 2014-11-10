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
     * @return IBridgeInterface
     */
    final protected function writeData()
    {

        return static::$DatabaseMaster;
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
}
