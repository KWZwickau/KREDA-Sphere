<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\System\Frontend\Authorization\Authorization;
use KREDA\Sphere\Application\System\Frontend\Consumer\Consumer;
use KREDA\Sphere\Application\System\Frontend\Database\Cache;
use KREDA\Sphere\Application\System\Frontend\Database\Status;
use KREDA\Sphere\Application\System\Frontend\Installer\Installer;
use KREDA\Sphere\Application\System\Frontend\Token\Token;
use KREDA\Sphere\Application\System\Service\Protocol;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DatabaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EyeOpenIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
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

        self::setupApplicationAccess( 'System' );

        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new CogWheelsIcon()
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System', __CLASS__.'::frontendSystem'
        );
        /**
         * Database
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database', __CLASS__.'::frontendDatabaseStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Status', __CLASS__.'::frontendDatabaseStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Database/Cache', __CLASS__.'::frontendDatabaseCache'
        );
        /**
         * Update
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update', __CLASS__.'::frontendUpdate'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Simulation', __CLASS__.'::frontendUpdateSimulation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Perform', __CLASS__.'::frontendUpdatePerform'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Consumer', __CLASS__.'::frontendConsumer'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Consumer/Create', __CLASS__.'::frontendConsumerCreate'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Account', __CLASS__.'::apiAccount'
        );
        /**
         * Authorization
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization', __CLASS__.'::frontendAuthorization'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Right', __CLASS__.'::frontendAuthorizationRight'
        )->setParameterDefault( 'RightName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege', __CLASS__.'::frontendAuthorizationPrivilege'
        )->setParameterDefault( 'PrivilegeName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access', __CLASS__.'::frontendAuthorizationAccess'
        )->setParameterDefault( 'AccessName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Role', __CLASS__.'::frontendAuthorizationRole'
        )->setParameterDefault( 'Access', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/ListRoleAccess', __CLASS__.'::frontendAuthorizationRoleAccess'
        )->setParameterDefault( 'Role', null )->setParameterDefault( 'Access', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/ListAccessPrivilege', __CLASS__.'::frontendAuthorizationAccessPrivilege'
        )->setParameterDefault( 'Access', null )->setParameterDefault( 'Privilege', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/ListPrivilegeRight', __CLASS__.'::frontendAuthorizationPrivilegeRight'
        )->setParameterDefault( 'Privilege', null )->setParameterDefault( 'Right', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Token', __CLASS__.'::frontendToken'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Token/Certification', __CLASS__.'::frontendTokenCertification'
        )->setParameterDefault( 'CredentialKey', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Protocol', __CLASS__.'::frontendProtocol'
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
     * @return Element|Stage
     */
    public function frontendSystem()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Systemeinstellungen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );

        ob_start();
        phpinfo();
        $PhpInfo = ob_get_clean();

        $View->setContent(
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

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Status', 'Datenbanken', new DatabaseIcon()
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
            '/Sphere/System/Authorization', 'Berechtigungen', new EyeOpenIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Token', 'Hardware-Schlüssel', new YubiKeyIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Protocol', 'Protokoll', new TaskIcon()
        );
    }

    /**
     * @return Stage
     */
    public function frontendConsumer()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationConsumer();
        return Consumer::stageSummary();
    }

    public function setupApplicationNavigationConsumer()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Consumer/Create', 'Mandant anlegen', new CogIcon()
        );

    }

    /**
     * @return Stage
     */
    public function frontendConsumerCreate()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationConsumer();
        return Consumer::stageConsumerCreate();
    }

    /**
     * @return Stage
     */
    public function frontendUpdate()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Installer::guiSummary();
    }

    public function setupApplicationNavigationUpdate()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Simulation', 'Simulation durchführen', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Perform', 'Update durchführen', new CogWheelsIcon()
        );

    }

    /**
     * @return Stage
     */
    public function frontendAuthorization()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorization();
    }

    public function setupApplicationNavigationAuthorization()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Role', 'Rollen', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/ListRoleAccess', 'Rollen - Zugriffslevel', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Access', 'Zugriffslevel', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/ListAccessPrivilege', 'Zugriffslevel - Privilegien', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Privilege', 'Privilegien', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/ListPrivilegeRight', 'Privilegien - Rechte', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Right', 'Rechte', new CogIcon()
        );
    }

    /**
     * @param null|string $RightName
     *
     * @return Stage
     */
    public function frontendAuthorizationRight( $RightName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationRight( $RightName );
    }

    /**
     * @param null|string $PrivilegeName
     *
     * @return Stage
     */
    public function frontendAuthorizationPrivilege( $PrivilegeName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationPrivilege( $PrivilegeName );
    }

    /**
     * @param null|string $AccessName
     *
     * @return Stage
     */
    public function frontendAuthorizationAccess( $AccessName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationAccess( $AccessName );
    }

    /**
     * @param null|string $Access
     *
     * @return Stage
     */
    public function frontendAuthorizationRole( $Access )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationRole( $Access );
    }

    /**
     * @param null|int $Role
     * @param null|int $Access
     *
     * @return Stage
     */
    public function frontendAuthorizationRoleAccess( $Role, $Access )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationRoleAccess( $Role, $Access );
    }

    /**
     * @return Stage
     */
    public function frontendUpdateSimulation()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Installer::guiUpdateSimulation();
    }

    /**
     * @return Stage
     */
    public function frontendUpdatePerform()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Installer::guiUpdatePerform();
    }

    /**
     * @return Stage
     */
    public function frontendDatabaseStatus()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationDatabase();
        return Status::stageDatabaseStatus();
    }

    public function setupApplicationNavigationDatabase()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Status', 'Status', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Cache', 'Cache', new CogIcon()
        );

    }

    /**
     * @return Stage
     */
    public function frontendDatabaseCache()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationDatabase();
        return Cache::stageDatabaseCache();
    }

    /**
     * @return Stage
     */
    public function frontendToken()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationToken();
        return Token::stageWelcome();
    }

    public function setupApplicationNavigationToken()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Token/Certification', 'Zertifizierung', new YubiKeyIcon()
        );

    }

    /**
     * @param null|string $CredentialKey
     *
     * @throws \Exception
     * @return Stage
     */
    public function frontendTokenCertification( $CredentialKey )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationToken();
        return Token::stageCertification( $CredentialKey );
    }

    /**
     * @return Stage
     */
    public function frontendProtocol()
    {

        $this->setupModuleNavigation();
        return Frontend\Protocol\Protocol::stageLive();
    }
}
