<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Consumer as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Consumer
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Consumer extends Authorization
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
            '/Sphere/System/Consumer/Create', __CLASS__.'::frontendCreate'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendCreate()
    {

        self::setupModuleNavigation();
        return Frontend::stageCreate();
    }
}
