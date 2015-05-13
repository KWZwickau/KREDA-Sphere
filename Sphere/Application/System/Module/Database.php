<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Database as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
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
            '/Sphere/System/Database', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Status', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Check', __CLASS__.'::frontendCheck'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Repair', __CLASS__.'::frontendRepair'
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
            '/Sphere/System/Database/Status', 'Status', new QuestionIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Check', 'Prüfung', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Repair', 'Reparatur', new CogWheelsIcon()
        );
    }

    /**
     * @return Stage
     */
    public static function frontendCheck()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCheck( true );
    }

    /**
     * @return Stage
     */
    public static function frontendRepair()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCheck( false );
    }
}
