<?php
namespace KREDA\Sphere\Application\Statistic;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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

        self::setupApplicationAccess( 'Statistic' );

        self::$Configuration = $Configuration;

        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Statistic', 'Statistik', new StatisticIcon()
        );
        self::registerClientRoute( self::$Configuration, '/Sphere/Statistic', __CLASS__.'::frontendStatistic' );
        return $Configuration;
    }

    /**
     * @return Stage
     */
    public function frontendStatistic()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Statistik' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

    }
}
