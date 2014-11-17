<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\System\Service\Database;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WrenchIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Client
 *
 * @package KREDA\Sphere\Application\System
 */
class Client extends Application
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
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new WrenchIcon()
        );
        self::buildRoute( self::$Configuration, '/Sphere/System', __CLASS__.'::apiMain' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Backup', __CLASS__.'::apiMain' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Update', __CLASS__.'::apiUpdate' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Database', __CLASS__.'::serviceDatabaseMain' );

        return $Configuration;
    }

    /**
     * @return Element|Landing
     */
    public function apiMain()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Systemeinstellungen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Backup', 'Backup', new GearIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new GearIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database', 'Datenbanken', new GearIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiUpdate()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( '' );
        $View->setContent( Update::getApi()->setupDataStructure() );
        return $View;
    }

    /**
     * @return Landing
     */
    public function serviceDatabaseMain()
    {

        $this->setupModuleNavigation();
        return Database::getApi( '/Sphere/System/Database' )->apiMain();
    }

}
