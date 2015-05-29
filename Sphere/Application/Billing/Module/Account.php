<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Billing;
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
            '/Sphere/Billing/Account/Fibu/Create', __CLASS__.'::frontendAccountCreate'
        )
            ->setParameterDefault( 'Account', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Fibu', __CLASS__.'::frontendAccountFibu'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Edit', __CLASS__.'::frontendEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Account', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Fibu/Activate', __CLASS__.'::frontendAccountFibuActivate'
        )   ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Fibu/Deactivate', __CLASS__.'::frontendAccountFibuDeactivate'
        )   ->setParameterDefault( 'Id', null );

        //////////////////////////////
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Debtor',__CLASS__.'::frontendDebtor'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Account/Debtor/Create',__CLASS__.'::frontendCreateDebtor'
        )->setParameterDefault( 'Debtor', null );
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
     * @return Stage
     */
    public static function frontendDebtor()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendDebtor();
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
     * @param $Debtor
     * @return Stage
     */
    public static function frontendCreateDebtor( $Debtor )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCreateDebtor( $Debtor );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account/Fibu', 'FIBU Konto', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account/Debtor', 'Debitoren Konto', new PersonIcon()
        );
    }

    /**
     * @param $Id
     * @param $Account
     * @return mixed
     */
    public static function frontendEdit( $Id, $Account )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendEdit( $Id, $Account );
    }
}