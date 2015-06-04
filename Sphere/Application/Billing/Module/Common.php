<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BasketIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CommodityIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DocumentIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Common
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Common extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity', 'Leistungen', new CommodityIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account', 'FIBU', new EditIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Banking', 'Debitor', new EditIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Basket', 'Fakturieren', new BasketIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice', 'Rechnungen', new DocumentIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Balance', 'Offene Posten', new DocumentIcon()
        );
    }
}
