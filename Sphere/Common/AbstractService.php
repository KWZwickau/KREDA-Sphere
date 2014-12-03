<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\IServiceInterface;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class AbstractService
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractService extends AbstractSetup implements IServiceInterface
{

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
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->addInstallProtocol( __CLASS__ );
        $this->addInstallProtocol( '<span class="text-danger">Missing Database-Schema Configuration!</span>' );
        return $this->getInstallProtocol( $Simulate );
    }

    /**
     * @return void
     */
    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->addInstallProtocol( __CLASS__ );
        $this->addInstallProtocol( '<span class="text-danger">Missing Database-Content Configuration!</span>' );
    }

    /**
     * @param string $Route Service Route
     *
     * @return null|string Client-Application Route
     */
    final protected function getClientServiceRoute( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return HttpKernel::getRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
    }

}
