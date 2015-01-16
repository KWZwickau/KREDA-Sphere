<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Database\Schema\EntityManager;
use KREDA\Sphere\IServiceInterface;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class AbstractService
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractService extends AbstractAddOn implements IServiceInterface
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

        return HttpKernel::getRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
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
}
