<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\System\Service\Database;
use KREDA\Sphere\Application\System\Service\Token;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CertificateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
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

        self::getDebugger()->addMethodCall( __METHOD__ );

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new WrenchIcon()
        );
        self::buildRoute( self::$Configuration, '/Sphere/System', __CLASS__.'::apiMain' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Update', __CLASS__.'::apiUpdate' );
        self::buildRoute( self::$Configuration, '/Sphere/System/Update/Simulation', __CLASS__.'::apiUpdateSimulation' );
        self::buildRoute( self::$Configuration, '/Sphere/System/Update/Perform', __CLASS__.'::apiUpdatePerform' );

        if (Access::getApi()->apiIsValidAccess( '/Sphere/System/Database/Status' )) {
            self::buildRoute( self::$Configuration, '/Sphere/System/Database', __CLASS__.'::apiDatabaseStatus' );
            self::buildRoute( self::$Configuration, '/Sphere/System/Database/Status', __CLASS__.'::apiDatabaseStatus' );
        }

        self::buildRoute( self::$Configuration, '/Sphere/System/Account', __CLASS__.'::apiAccount' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Token', __CLASS__.'::apiToken' );
        self::buildRoute( self::$Configuration, '/Sphere/System/Token/Certification',
            __CLASS__.'::apiTokenCertification' );

        return $Configuration;
    }

    /**
     * @return Element|Landing
     */
    public function apiMain()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Systemeinstellungen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (Access::getApi()->apiIsValidAccess( '/Sphere/System/Database/Status' )) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/System/Database', 'Datenbanken', new TaskIcon()
            );
        }
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new FlashIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Account', 'Benutzerkonten', new PersonIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Token', 'Hardware-Schlüssel', new CertificateIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiUpdate()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdate();
    }

    public function setupServiceUpdate()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Simulation', 'Simulation durchführen', new GearIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Perform', 'Update durchführen', new GearIcon()
        );

    }

    /**
     * @return Landing
     */
    public function apiUpdateSimulation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdateSimulation();
    }

    /**
     * @return Landing
     */
    public function apiUpdatePerform()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdatePerform();
    }

    /**
     * @return Landing
     */
    public function apiDatabaseStatus()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        return Database::getApi( '/Sphere/System/Database' )->apiStatus();
    }

    /**
     * @return Landing
     */
    public function apiToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceToken();
        return Token::getApi()->apiToken();
    }

    public function setupServiceToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Token/Certification', 'Zertifizierung', new CertificateIcon()
        );

    }

    /**
     * @param null|string $CredentialKey
     *
     * @throws \Exception
     * @return Landing
     */
    public function apiTokenCertification( $CredentialKey = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceToken();
        return Token::getApi()->apiTokenCertification( $CredentialKey );
    }
}
