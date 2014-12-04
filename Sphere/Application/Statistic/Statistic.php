<?php
namespace KREDA\Sphere\Application\Statistic;

use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
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
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        self::$Configuration = $Configuration;
        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Statistic', 'Statistik', new StatisticIcon()
        );
        self::registerClientRoute( self::$Configuration, '/Sphere/Statistic', __CLASS__.'::apiMain' );
        return $Configuration;
    }

    public function apiMain()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Statistik' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

    }
}
