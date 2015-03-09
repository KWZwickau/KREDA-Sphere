<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Database as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Database
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Database extends Cache
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
            '/Sphere/System/Database/Status', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Check', __CLASS__.'::frontendCheck'
        )->setParameterDefault( 'Simulation', true );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Repair', __CLASS__.'::frontendCheck'
        )->setParameterDefault( 'Simulation', false );
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
            '/Sphere/System/Database/Check', 'Prüfung', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Repair', 'Reparatur', new CogWheelsIcon()
        );
    }

    /**
     * @param $Simulation
     *
     * @return Stage
     */
    public static function frontendCheck( $Simulation )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCheck( $Simulation );
    }
}
