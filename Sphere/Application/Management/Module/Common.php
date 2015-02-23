<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Common
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Common extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
    {

//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Campus', 'Immobilien', new BuildingIcon()
//        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Person', 'Personen', new GroupIcon()
        );

//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Education/Group', 'Klassen', new ClusterIcon()
//        );
//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Education/Subject', 'Fächer', new ShareIcon()
//        );
//
//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Education/Period', 'Zeiten', new TimeIcon()
//        );
//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Education/Mission', 'Aufträge', new BriefcaseIcon()
//        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Token', 'Hardware-Schlüssel', new YubiKeyIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Account', 'Benutzerkonten', new PersonKeyIcon()
        );
    }

}
