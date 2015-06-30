<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
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
            '/Sphere/Management/Person', 'Personen', new GroupIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Group', 'Gruppen', new GroupIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear', 'Zeiten', new TimeIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Education', 'Klassen und Fächer', new ClusterIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Company', 'Firmen', new HomeIcon()
        );
//        self::addModuleNavigationMain( self::$Configuration,
//            '/Sphere/Management/Education/Mission', 'Aufträge', new BriefcaseIcon()
//        );
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Token' )) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/Management/Token', 'Hardware-Schlüssel', new YubiKeyIcon()
            );
        }
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Account' )) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/Management/Account', 'Benutzerkonten', new PersonKeyIcon()
            );
        }
    }

}
