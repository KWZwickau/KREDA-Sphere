<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Commodity as Frontend;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Commodity extends Common
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
            '/Sphere/Billing/Commodity/Create', __CLASS__.'::frontendCreate'
        )
            ->setParameterDefault( 'Name', null )
            ->setParameterDefault( 'Description', null );
    }

    public static function frontendCreate( $Name, $Description )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCreate( $Name, $Description);
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity/Create', 'Leistung anlegen', new PersonIcon()
        );
    }
}