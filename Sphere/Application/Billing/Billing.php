<?php
namespace KREDA\Sphere\Application\Billing;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Billing
 *
 * @package KREDA\Sphere\Application\Billing
 */
class Billing extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        /**
         * Configuration
         */
        self::setupApplicationAccess( 'Billing' );
        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        self::addClientNavigationMain( self::$Configuration, '/Sphere/Billing', 'Fakturierung', new MoneyIcon() );
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

    }
}
