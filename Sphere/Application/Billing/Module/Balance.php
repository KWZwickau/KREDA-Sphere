<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Application\Billing\Frontend\Balance as Frontend;

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
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Balance/Payment', __CLASS__.'::frontendPayment'
        );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Balance/Payment', 'Importierte Zahlungen', new MoneyIcon()
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
     * @return Stage
     */
    public static function frontendPayment()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendPayment();
    }
}