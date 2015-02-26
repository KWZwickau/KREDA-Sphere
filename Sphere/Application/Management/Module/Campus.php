<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Campus\Campus as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Campus
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Campus extends Person
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

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Campus', __CLASS__.'::frontendCampus'
        );
    }


    /**
     * @return Stage
     */
    public static function frontendCampus()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCampus();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management', 'Gebäude', new BuildingIcon()
        );
    }
}
