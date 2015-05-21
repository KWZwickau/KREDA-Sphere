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
        )->setParameterDefault( 'Account', null );
        //////////////////////////////
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/AddDebitor',__CLASS__.'::frontendAddDebitor'
        )->setParameterDefault( 'Debitor', null );
        //////////////////////////////

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
     * @param $Account
     * @return Stage
     */
    public static function frontendAccountCreate( $Account )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::fontendCreateAccount( $Account );
    }

    /**
     * @param $Debitor
     * @return Stage
     */
    public static function frontendAddDebitor( $Debitor )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendAddDebitor( $Debitor );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account/Create', 'Anlegen', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account/AddDebitor', 'Debitor Anlegen', new PersonIcon()
        );
    }
}