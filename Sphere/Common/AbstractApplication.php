<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\IconParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\IApplicationInterface;
use MOC\V\Component\Router\Component\Parameter\Repository\RouteParameter;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class AbstractApplication
 *
 * @package KREDA\Sphere
 */
abstract class AbstractApplication extends AbstractAddOn implements IApplicationInterface
{

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Method
     *
     * @return RouteParameter
     */
    protected static function registerClientRoute( Configuration &$Configuration, $Url, $Method )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Route = new RouteParameter( $Url, $Method );
        $Configuration->getClientRouter()->addRoute( $Route );

        return $Route;
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param Icon          $Icon
     */
    protected static function addClientNavigationMeta( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Configuration->getClientNavigation()->addLinkToMeta(
            new LevelClient\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
        if (self::prepareParameterActive( $Url )) {
            $Configuration->getClientNavigation()->addBreadcrumb( $Name );
        }
    }

    /**
     * @param string $Value
     *
     * @return UrlParameter
     */
    final private static function prepareParameterUrl( $Value )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return new UrlParameter( $Value );
    }

    /**
     * @param string $Value
     *
     * @return NameParameter
     */
    final private static function prepareParameterName( $Value )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return new NameParameter( $Value );
    }

    /**
     * @param Icon $Value
     *
     * @return Icon|IconParameter
     */
    final private static function prepareParameterIcon( Icon $Value )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        if (null !== $Value) {
            $Value = new IconParameter( $Value );
        }
        return $Value;
    }

    /**
     * @param UrlParameter $Value
     *
     * @return bool
     */
    final private static function prepareParameterActive( UrlParameter $Value )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Request = HttpKernel::getRequest();
        return 0 === strpos( $Request->getUrlBase().$Request->getPathInfo(), $Value->getValue() );
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param Icon          $Icon
     */
    protected static function addClientNavigationMain( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Configuration->getClientNavigation()->addLinkToMain(
            new LevelClient\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
        if (self::prepareParameterActive( $Url )) {
            $Configuration->getClientNavigation()->addBreadcrumb( $Name );
        }
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param Icon          $Icon
     */
    protected static function addModuleNavigationMain( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Configuration->getModuleNavigation()->addLinkToMain(
            new LevelModule\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
        if (self::prepareParameterActive( $Url )) {
            $Configuration->getModuleNavigation()->addBreadcrumb( $Name );
        }
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param Icon          $Icon
     */
    protected static function addApplicationNavigationMain(
        Configuration &$Configuration,
        $Url,
        $Name,
        Icon $Icon = null
    ) {

        self::getDebugger()->addMethodCall( __METHOD__ );

        $Configuration->getApplicationNavigation()->addLinkToMain(
            new LevelApplication\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
    }

    abstract protected function setupModuleNavigation();
}
