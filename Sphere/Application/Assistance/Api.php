<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Assistance\Client\Nuff;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Configuration;

class Api extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::buildNavigationMeta( self::$Configuration,
            '/Sphere/Assistance', 'Hilfe', new QuestionIcon()
        );
        self::buildRoute( self::$Configuration, '/Sphere/Assistance', __CLASS__.'::Main' );
        self::buildRoute( self::$Configuration, '/Sphere/Assistance/Support', __CLASS__.'::Main' );
        self::buildRoute( self::$Configuration, '/Sphere/Assistance/Support/Account', __CLASS__.'::Main' );
        return $Configuration;
    }

    public function Main()
    {

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Assistance/Anmeldung', 'Anmeldung', new LockIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Assistance/Support', 'Support', new QuestionIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Assistance/Support/Account', 'Account', new LockIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Assistance/Support/Account', 'Fehler', new GearIcon()
        );

        return new Nuff();
    }
}
