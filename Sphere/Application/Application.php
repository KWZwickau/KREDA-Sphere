<?php
namespace KREDA\Sphere\Application;

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
 * Class Application
 *
 * @package KREDA\Sphere
 */
abstract class Application implements IApplicationInterface
{

    /**
     * @return Debugger
     */
    public static function getDebugger()
    {

        return new Debugger();
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Method
     *
     * @return RouteParameter
     */
    protected static function buildRoute( Configuration &$Configuration, $Url, $Method )
    {

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
    private static function prepareParameterUrl( $Value )
    {

        return new UrlParameter( $Value );
    }

    /**
     * @param string $Value
     *
     * @return NameParameter
     */
    private static function prepareParameterName( $Value )
    {

        return new NameParameter( $Value );
    }

    /**
     * @param Icon $Value
     *
     * @return Icon|IconParameter
     */
    private static function prepareParameterIcon( Icon $Value )
    {

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
    private static function prepareParameterActive( UrlParameter $Value )
    {

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

        $Configuration->getClientNavigation()->addLinkToMain(
            $Link = new LevelClient\Link(
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
