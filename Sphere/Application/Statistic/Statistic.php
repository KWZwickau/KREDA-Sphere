<?php
namespace KREDA\Sphere\Application\Statistic;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\StatisticIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Statistic
 *
 * @package KREDA\Sphere\Application\Statistic
 */
class Statistic extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        /**
         * Configuration
         */
        self::setupApplicationAccess( 'Statistic' );
        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Statistic', 'Statistik', new StatisticIcon()
        );
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

    }
}
