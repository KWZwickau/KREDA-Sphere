<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
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
            '/Sphere/Billing/Commodity', 'Leistungen', new GroupIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account', 'Account', new EditIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/Commodity/Select', 'Fakturieren', new EditIcon()
        );
    }
}
