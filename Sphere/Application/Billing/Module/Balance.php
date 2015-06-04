<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Balance as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Balance extends Common
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
            '/Sphere/Billing/Balance', __CLASS__.'::frontendBalance'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendBalance()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBalance();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }
}
