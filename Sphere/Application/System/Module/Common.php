<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DatabaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EyeOpenIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HistoryIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Common
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Common extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Cache/Status', 'Cache', new HistoryIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Status', 'Datenbank', new DatabaseIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new FlashIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Protocol/Status', 'Protokoll', new TaskIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization', 'Berechtigungen', new EyeOpenIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Consumer/Create', 'Mandanten', new HomeIcon()
        );
    }

}
