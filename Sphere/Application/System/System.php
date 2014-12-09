<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\System\Service\Database;
use KREDA\Sphere\Application\System\Service\Token;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CertificateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WrenchIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class System
 *
 * @package KREDA\Sphere\Application\System
 */
class System extends AbstractApplication
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

        self::getDebugger()->addMethodCall( __METHOD__ );

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new WrenchIcon()
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System', __CLASS__.'::apiMain'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database', __CLASS__.'::apiDatabaseStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Status', __CLASS__.'::apiDatabaseStatus'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update', __CLASS__.'::apiUpdate'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Simulation', __CLASS__.'::apiUpdateSimulation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Perform', __CLASS__.'::apiUpdatePerform'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Consumer', __CLASS__.'::apiConsumer'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Account', __CLASS__.'::apiAccount'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Token', __CLASS__.'::apiToken'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Token/Certification', __CLASS__.'::apiTokenCertification'
        );

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

        ob_start();
        phpinfo();
        $PhpInfo = ob_get_clean();

        $View->setContent(
            '<style type="text/css">
            #phpinfo { text-align: left; }
            #phpinfo a { color: red;}
            #phpinfo a:link { color: red;}
            #phpinfo a:hover {  color: red;}
            #phpinfo table { width: 100%; }
            #phpinfo td, th {  border: 1px solid silver; padding: 5px; }
            #phpinfo th { padding: 5px; }
            #phpinfo h1 { font-size: 1.3em; }
            #phpinfo h2 { font-size: 1.2em; text-transform: capitalize; border-bottom: 1px solid red; }
            #phpinfo .p { margin: 0; }
            #phpinfo .e { background: #CCC; width: 30%; }
            #phpinfo .h { background: #DDD; }
            #phpinfo img { display: none; }
            </style>'.
            '<div id="phpinfo">'.
            preg_replace( '!,!', ', ',
                preg_replace( '!<th>(enabled)\s*</th>!i',
                    '<th><span class="badge badge-success">$1</span></th>',
                    preg_replace( '!<td class="v">(On|enabled|active|Yes)\s*</td>!i',
                        '<td class="v"><span class="badge badge-success">$1</span></td>',
                        preg_replace( '!<td class="v">(Off|disabled|No)\s*</td>!i',
                            '<td class="v"><span class="badge badge-danger">$1</span></td>',
                            preg_replace( '!<i>no value</i>!',
                                '<span class="label label-warning">no value</span>',
                                preg_replace( '%^.*<body>(.*)</body>.*$%ms', '$1', $PhpInfo )
                            )
                        )
                    )
                )
            )
            .'</div>'
        );

        return $View;
    }

    public function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database', 'Datenbanken', new TaskIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new FlashIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Consumer', 'Mandanten', new HomeIcon()
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
        return Database::getApi( '/Sphere/System/Database' )->guiDatabaseStatus();
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
