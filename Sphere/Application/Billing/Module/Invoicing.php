<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoicing as Frontend;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Invoicing extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoicing/Commodity/Select', 'Fakturierung anlegen', new PersonIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoicing/Commodity/Select', __CLASS__.'::frontendCommoditySelect'
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
}