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

    private static function prepareParameterUrl( $Value )
    {

        return new UrlParameter( $Value );
    }

    private static function prepareParameterName( $Value )
    {

        return new NameParameter( $Value );
    }

    private static function prepareParameterIcon( Icon $Value )
    {

        if (null !== $Value) {
            $Value = new IconParameter( $Value );
        }
        return $Value;
    }

    private static function prepareParameterActive( UrlParameter $Value )
    {

        $Request = HttpKernel::getRequest();
        return 0 === strpos( $Request->getUrlBase().$Request->getPathInfo(), $Value->getValue() );
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

    //from http://stackoverflow.com/questions/768431/how-to-make-a-redirect-in-php
    /*
    protected function doRedirect( $Url, $Code = 302 )
    {
        $Request = HttpKernel::getRequest();
        $Url = $Request->getUrlBase().$Url;

        if (headers_sent() !== true) {
            if (strlen( session_id() ) > 0) {
                session_regenerate_id( true );
                session_write_close();
            }
            if (strncmp( 'cgi', PHP_SAPI, 3 ) === 0) {
                header( sprintf( 'Status: %03u', $Code ), true, $Code );
            }
            header( 'Location: '.$Url, true, ( preg_match( '~^30[1237]$~', $Code ) > 0 ) ? $Code : 302 );
        } else {
            ?>
            <meta http-equiv="Refresh" content="1; URL=<?php echo $Url; ?>">
            <script language=javascript>setTimeout( "location.href='<?php echo $Url; ?>'", 1 );</script>
        <?php
        }
        exit();
    }
    */
}
