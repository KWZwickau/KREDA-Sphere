<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\System\Frontend\Consumer\Consumer;
use KREDA\Sphere\Application\System\Frontend\Installer\Installer;
use KREDA\Sphere\Application\System\Frontend\Status\Status;
use KREDA\Sphere\Application\System\Service\Database;
use KREDA\Sphere\Application\System\Service\Protocol;
use KREDA\Sphere\Application\System\Service\Token;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new WrenchIcon()
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System', __CLASS__.'::apiMain'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database', __CLASS__.'::frontendDatabase_Status'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Status', __CLASS__.'::frontendDatabase_Status'
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
            '/Sphere/System/Consumer/Create', __CLASS__.'::apiConsumerCreate'
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

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Protocol', __CLASS__.'::apiProtocol'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Protocol/Live', __CLASS__.'::apiProtocolLive'
        );

        return $Configuration;
    }

    /**
     * @return Service\Update
     */
    public static function serviceUpdate()
    {

        return Update::getApi();
    }

    /**
     * @return Service\Protocol
     */
    public static function serviceProtocol()
    {

        return Protocol::getApi();
    }

    /**
     * @return Service\Database
     */
    public static function serviceDatabase()
    {

        return Database::getApi();
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

        ob_start();
        phpinfo();
        $PhpInfo = ob_get_clean();

        $GlyphIconStyle = '<style>';
        $GlyphIconTable = '';

        $GlyphIconTable .= '<h2>Halflings</h2><div class="clearfix">';

        for ($Run = 0; $Run < 273; $Run++) {
            $GlyphIconStyle .= '.glyphicon-halfling-'.$Run.':before { font-family: "Glyphicons Halflings"; content: "\e'.str_pad( $Run,
                    3, '0', STR_PAD_LEFT ).'"; font-size: 30px; }';
            $GlyphIconTable .= '<div class="pull-left" style="width: 70px; padding: 5px; margin: 5px; border: 1px dotted silver;"><span class="glyphicon glyphicon-halfling-'.$Run.'"></span><hr style="margin:0;"/>\e'.str_pad( $Run,
                    3, '0', STR_PAD_LEFT ).'</div>';
        }

        $GlyphIconTable .= '</div><hr/><h2>Regular</h2>';
        $GlyphIconTable .= '<div class="clearfix">';

        for ($Run = 0; $Run < 611; $Run++) {
            $GlyphIconStyle .= '.glyphicon-regular-'.$Run.':before { font-family: "Glyphicons Regular"; content: "\e'.str_pad( $Run,
                    3, '0', STR_PAD_LEFT ).'"; font-size: 30px; }';
            $GlyphIconTable .= '<div class="pull-left" style="width: 70px; padding: 5px; margin: 5px; border: 1px dotted silver;"><span class="glyphicon glyphicon-regular-'.$Run.'"></span><hr style="margin:0;"/>\e'.str_pad( $Run,
                    3, '0', STR_PAD_LEFT ).'</div>';
        }

        $GlyphIconTable .= '</div>';
        $GlyphIconStyle .= '</style>';

        $View->setContent(
//            '<div id="phpinfo">'.
//            preg_replace( '!,!', ', ',
//                preg_replace( '!<th>(enabled)\s*</th>!i',
//                    '<th><span class="badge badge-success">$1</span></th>',
//                    preg_replace( '!<td class="v">(On|enabled|active|Yes)\s*</td>!i',
//                        '<td class="v"><span class="badge badge-success">$1</span></td>',
//                        preg_replace( '!<td class="v">(Off|disabled|No)\s*</td>!i',
//                            '<td class="v"><span class="badge badge-danger">$1</span></td>',
//                            preg_replace( '!<i>no value</i>!',
//                                '<span class="label label-warning">no value</span>',
//                                preg_replace( '%^.*<body>(.*)</body>.*$%ms', '$1', $PhpInfo )
//                            )
//                        )
//                    )
//                )
//            )
//            .'</div>'

            '<div>'.$GlyphIconStyle.$GlyphIconTable.'</div>'
        );
        return $View;
    }

    public function setupModuleNavigation()
    {

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
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Protocol', 'Protokoll', new TaskIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiConsumer()
    {

        $this->setupModuleNavigation();
        $this->setupServiceConsumer();
        return Consumer::guiSummary();
    }

    public function setupServiceConsumer()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Consumer/Create', 'Mandant anlegen', new GearIcon()
        );

    }

    /**
     * @return Stage
     */
    public function apiConsumerCreate()
    {

        $this->setupModuleNavigation();
        $this->setupServiceConsumer();
        return Consumer::guiConsumerCreate();
    }

    /**
     * @return Landing
     */
    public function apiUpdate()
    {

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Installer::guiSummary();
    }

    public function setupServiceUpdate()
    {

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

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Installer::guiUpdateSimulation();
    }

    /**
     * @return Landing
     */
    public function apiUpdatePerform()
    {

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Installer::guiUpdatePerform();
    }

    /**
     * @return Stage
     */
    public function frontendDatabase_Status()
    {

        $this->setupModuleNavigation();
        return Status::stageDatabaseStatus();
    }

    /**
     * @return Landing
     */
    public function apiToken()
    {

        $this->setupModuleNavigation();
        $this->setupServiceToken();
        return Token::getApi()->apiToken();
    }

    public function setupServiceToken()
    {

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

        $this->setupModuleNavigation();
        $this->setupServiceToken();
        return Token::getApi()->apiTokenCertification( $CredentialKey );
    }

    /**
     * @return Stage
     */
    public function apiProtocol()
    {

        $this->setupModuleNavigation();
        return Frontend\Protocol\Protocol::stageLive();
    }
}
