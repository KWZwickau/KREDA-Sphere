<?php
namespace KREDA\Sphere\Application\Grade;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Grade\Client\Entrance;
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
            '/Sphere/Grade', 'Zensuren', new StatisticIcon()
        );
        self::buildRoute(self::$Configuration, '/Sphere/Grade', __CLASS__ . '::apiMain');
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
             '/Sphere/Management/Class', 'Zensurentypen', new StatisticIcon()
         );
    }
}
