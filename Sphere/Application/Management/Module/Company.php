<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Company as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Company extends Common
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
            '/Sphere/Management/Company', __CLASS__.'::frontendCompanyList'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendCompanyList()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCompanyList();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }
}
