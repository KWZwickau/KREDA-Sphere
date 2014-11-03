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
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Method
     *
     * @return RouteParameter
     */
    protected static function buildRoute( Configuration &$Configuration, $Url, $Method )
    {

        $Route = new RouteParameter( $Url, $Method );
        $Configuration->getRouter()->addRoute( $Route );

        return $Route;
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param Icon          $Icon
     */
    protected static function buildNavigationMeta( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        $Configuration->getNavigation()->addLinkToMeta(
            new LevelClient\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
    }

    protected static function buildNavigationMain( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        $Configuration->getNavigation()->addLinkToMain(
            new LevelClient\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
    }

    protected static function buildModuleMain( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        $Configuration->getModuleNavigation()->addLinkToMain(
            new LevelModule\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
    }

    protected static function buildMenuMain( Configuration &$Configuration, $Url, $Name, Icon $Icon = null )
    {

        $Configuration->getMenuNavigation()->addLinkToMain(
            new LevelApplication\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
    }

    private function prepareParameterUrl( $Value )
    {

        return new UrlParameter( $Value );
    }

    private function prepareParameterName( $Value )
    {

        return new NameParameter( $Value );
    }

    private function prepareParameterIcon( Icon $Value )
    {

        if (null !== $Value) {
            $Value = new IconParameter( $Value );
        }
        return $Value;
    }

    private function prepareParameterActive( UrlParameter $Value )
    {

        $Request = HttpKernel::getRequest();
        return 0 === strpos( $Request->getUrlBase().$Request->getPathInfo(), $Value->getValue() );
    }
}
