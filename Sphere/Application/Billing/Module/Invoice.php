<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Invoice extends Common
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
            '/Sphere/Billing/Invoice/Commodity/Select', __CLASS__.'::frontendCommoditySelect'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendCommoditySelect()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCommoditySelect();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/Commodity/Select', 'Fakturierung anlegen', new PersonIcon()
        );
    }
}
