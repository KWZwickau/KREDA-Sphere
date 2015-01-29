<?php
namespace KREDA\Sphere\Common;

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
    /** @var AbstractEntity[] $EntityByIdCache */
    protected static $EntityByIdCache = array();

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
     * @param string $Name
     * @param int    $Id
     * @param bool   $Cache
     *
     * @return bool|AbstractEntity
     */
    final public function getEntityById( $Name, $Id, $Cache = true )
    {

        if ($Cache && isset( static::$EntityByIdCache[$Name.$Id] )) {
            return static::$EntityByIdCache[$Name.$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( $Name, $Id );
        static::$EntityByIdCache[$Name.$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
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
     * @param string $Route Service Route
     *
     * @return null|string Client-Application Route
     */
    final protected function getClientServiceRoute( $Route )
    {

        return $this->extensionRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
    }
}
