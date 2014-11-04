<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Management\Client\Entrance;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\StatisticIcon;
use KREDA\Sphere\Client\Configuration;

class Api extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi(Configuration $Configuration)
    {

        self::$Configuration = $Configuration;
        self::buildNavigationMain(self::$Configuration,
            '/Sphere/Management', 'Verwaltung', new StatisticIcon()
        );
        self::buildRoute(self::$Configuration, '/Sphere/Management', __CLASS__ . '::apiMain');
        return $Configuration;
    }

    public function apiMain()
    {
        $this->setupModule();
        return new Entrance();
    }

    public function setupModule()
    {

        self::buildModuleMain(self::$Configuration,
            '/Sphere/Management/Class', 'Personen', new StatisticIcon()
        );
        self::buildModuleMain(self::$Configuration,
            '/Sphere/Management/Class', 'Klassen', new StatisticIcon()
        );
        self::buildModuleMain(self::$Configuration,
            '/Sphere/Management/Class', 'Schulfächer', new StatisticIcon()
        );
        self::buildModuleMain(self::$Configuration,
            '/Sphere/Management/Class', 'Ferienzeiten', new StatisticIcon()
        );
    }
}
