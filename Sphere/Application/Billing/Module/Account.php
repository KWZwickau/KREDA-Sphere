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
            '/Sphere/Billing/Account/Create', __CLASS__.'::frontendAccountCreate'
        )
            ->setParameterDefault( 'Account', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account', __CLASS__.'::frontendAccountFibu'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Edit', __CLASS__.'::frontendEditAccountFibu'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Account', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Activate', __CLASS__.'::frontendAccountFibuActivate'
        )   ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Deactivate', __CLASS__.'::frontendAccountFibuDeactivate'
        )   ->setParameterDefault( 'Id', null );

    }

    /**
     * @return Stage
     */
    public static function frontendAccountFibu()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendAccountFibu();

    }

    /**
     * @param $Account
     * @return Stage
     */
    public static function frontendAccountCreate( $Account )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCreateAccount($Account);
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendAccountFibuActivate( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendAccountFibuActivate( $Id );
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendAccountFibuDeactivate( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendAccountFibuDeactivate( $Id );
    }

    /**
     * @param $Id
     * @param $Account
     * @return mixed
     */
    public static function frontendEditAccountFibu( $Id, $Account )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendEditAccountFibu( $Id, $Account );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }

}