<?php
namespace KREDA\Sphere\Application\Statistic;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\StatisticIcon;
use KREDA\Sphere\Client\Configuration;

class Client extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Statistic', 'Statistk', new StatisticIcon()
        );
        self::buildRoute( self::$Configuration, '/Sphere/Statistic', __CLASS__.'::apiMain' );
        return $Configuration;
    }

    public function apiMain()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Statistik' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

    }
}