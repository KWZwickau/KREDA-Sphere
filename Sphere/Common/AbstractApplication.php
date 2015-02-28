<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\IconParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\IApplicationInterface;
use MOC\V\Component\Router\Component\Parameter\Repository\RouteParameter;

/**
 * Class AbstractApplication
 *
 * - Methods MUST RETURN Stage
 *
 * @package KREDA\Sphere
 */
abstract class AbstractApplication extends AbstractExtension implements IApplicationInterface
{

    /** @var null $ActiveRequest */
    private static $ActiveRequest = null;

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Method
     *
     * @return RouteParameter
     */
    protected static function registerClientRoute( Configuration &$Configuration, $Url, $Method )
    {

        $Route = new RouteParameter( $Url, $Method );
        $Configuration->getClientRouter()->addRoute( $Route );

        return $Route;
    }

    /**
     * @return string
     */
    final protected static function getUrlBase()
    {

        return self::extensionRequest()->getUrlBase();
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param AbstractIcon  $Icon
     */
    protected static function addClientNavigationMeta(
        Configuration &$Configuration,
        $Url,
        $Name,
        AbstractIcon $Icon = null
    ) {

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

        return new UrlParameter( $Value );
    }

    /**
     * @param string $Value
     *
     * @return NameParameter
     */
    final private static function prepareParameterName( $Value )
    {

        return new NameParameter( $Value );
    }

    /**
     * @param AbstractIcon $Value
     *
     * @return AbstractIcon|IconParameter
     */
    final private static function prepareParameterIcon( AbstractIcon $Value = null )
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
    final private static function prepareParameterActive( UrlParameter $Value )
    {

        if (null === self::$ActiveRequest) {
            self::$ActiveRequest = self::extensionRequest();
        }
        return 0 === strpos( self::$ActiveRequest->getUrlBase().self::$ActiveRequest->getPathInfo(),
            $Value->getValue() );
    }

    /**
     * @param Configuration $Configuration
     * @param string        $Url
     * @param string        $Name
     * @param AbstractIcon  $Icon
     */
    protected static function addClientNavigationMain(
        Configuration &$Configuration,
        $Url,
        $Name,
        AbstractIcon $Icon = null
    ) {

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
     * @param AbstractIcon  $Icon
     */
    protected static function addModuleNavigationMain(
        Configuration &$Configuration,
        $Url,
        $Name,
        AbstractIcon $Icon = null
    ) {

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
     * @param AbstractIcon  $Icon
     */
    protected static function addApplicationNavigationMain(
        Configuration &$Configuration,
        $Url,
        $Name,
        AbstractIcon $Icon = null
    ) {

        $Configuration->getApplicationNavigation()->addLinkToMain(
            new LevelApplication\Link(
                $Url = self::prepareParameterUrl( $Url ),
                self::prepareParameterName( $Name ),
                self::prepareParameterIcon( $Icon ),
                self::prepareParameterActive( $Url )
            )
        );
        if (self::prepareParameterActive( $Url )) {
            $Configuration->getApplicationNavigation()->addBreadcrumb( $Name );
        }
    }

    /**
     * @param string $ApplicationName
     */
    final protected static function setupApplicationAccess( $ApplicationName )
    {

        $tblRight = Gatekeeper::serviceAccess()->executeCreateApplicationRight( $ApplicationName );
        $tblPrivilege = Gatekeeper::serviceAccess()->executeCreateApplicationPrivilege( $ApplicationName,
            'Administrator' );
        $tblAccess = Gatekeeper::serviceAccess()->executeCreateApplicationAccess( $ApplicationName, 'Administrator' );
        Gatekeeper::serviceAccess()->executeAddPrivilegeRight( $tblPrivilege, $tblRight );
        Gatekeeper::serviceAccess()->executeAddAccessPrivilege( $tblAccess, $tblPrivilege );
        $tblRole = Gatekeeper::serviceAccount()->entityAccountRoleByName( 'System' );
        Gatekeeper::serviceAccount()->executeAddRoleAccess( $tblRole, $tblAccess );
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {
    }
}
