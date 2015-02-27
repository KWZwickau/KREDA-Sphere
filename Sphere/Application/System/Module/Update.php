<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Update as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Update extends Database
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Simulation', __CLASS__.'::frontendSimulation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Install', __CLASS__.'::frontendInstall'
        );
    }


    /**
     * @param bool $Clear
     *
     * @return Stage
     */
    public static function frontendStatus( $Clear )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageStatus( $Clear );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Simulation', 'Simulation', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Install', 'Installation', new CogWheelsIcon()
        );

    }

    /**
     * @return Stage
     */
    public static function frontendSimulation()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSimulation();
    }

    /**
     * @return Stage
     */
    public static function frontendInstall()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageInstall();
    }
}
