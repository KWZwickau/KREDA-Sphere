<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Application\Billing\Frontend\Account as Frontend;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Account extends Common
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
            '/Sphere/Billing/Account', __CLASS__.'::frontendAccount'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Create', __CLASS__.'::frontendAccountCreate'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendAccount()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendAccount();

    }

    /**
     * @return Stage
     */
    public static function frontendAccountCreate()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::fontendCreateAccount();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account/Create', 'Anlegen', new PersonIcon()
        );
    }
}