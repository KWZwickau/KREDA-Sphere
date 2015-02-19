<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\System\Frontend\Authorization\Authorization;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DatabaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EyeOpenIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
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
            '/Sphere/System/Database/Status', __CLASS__.'::frontendDatabaseStatus'
        );
        /**
         * Cache
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Cache/Status', __CLASS__.'::frontendCacheStatus'
        );
        /**
         * Update
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update', __CLASS__.'::frontendUpdateStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Simulation', __CLASS__.'::frontendUpdateSimulation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Update/Perform', __CLASS__.'::frontendUpdatePerform'
        );

        /**
         * Consumer
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Consumer/Create', __CLASS__.'::frontendConsumerCreate'
        );

        /**
         * Authorization
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization', __CLASS__.'::frontendAuthorization'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Role', __CLASS__.'::frontendAuthorizationRole'
        )->setParameterDefault( 'RoleName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access', __CLASS__.'::frontendAuthorizationAccess'
        )->setParameterDefault( 'AccessName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege', __CLASS__.'::frontendAuthorizationPrivilege'
        )->setParameterDefault( 'PrivilegeName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Right', __CLASS__.'::frontendAuthorizationRight'
        )->setParameterDefault( 'RightName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Role/Access', __CLASS__.'::frontendAuthorizationRoleAccess'
        )->setParameterDefault( 'Role', null )->setParameterDefault( 'Access', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access/Privilege', __CLASS__.'::frontendAuthorizationAccessPrivilege'
        )->setParameterDefault( 'Access', null )->setParameterDefault( 'Privilege', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege/Right', __CLASS__.'::frontendAuthorizationPrivilegeRight'
        )->setParameterDefault( 'Privilege', null )->setParameterDefault( 'Right', null );

        /**
         * Protocol
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Protocol/Status', __CLASS__.'::frontendProtocolStatus'
        );

        return $Configuration;
    }

    /**
     * @return Service\Update
     */
    public static function serviceUpdate()
    {

        return Service\Update::getApi();
    }

    /**
     * @return Service\Protocol
     */
    public static function serviceProtocol()
    {

        return Service\Protocol::getApi();
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

    /**
     * @return void
     */
    protected function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Status', 'Datenbank', new DatabaseIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Protocol/Status', 'Protokoll', new TaskIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Cache/Status', 'Cache', new DatabaseIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new FlashIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Consumer/Create', 'Mandanten', new HomeIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization', 'Berechtigungen', new EyeOpenIcon()
        );
    }

    /**
     * @return Stage
     */
    public function frontendConsumerCreate()
    {

        $this->setupModuleNavigation();
        return Frontend\Consumer::stageCreate();
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

    /**
     * @return void
     */
    protected function setupApplicationNavigationAuthorization()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Role', 'Rollen', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Access', 'Zugriffslevel', new CogIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Authorization/Privilege', 'Privilegien', new CogIcon()
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
     * @param null|string $RoleName
     *
     * @return Stage
     */
    public function frontendAuthorizationRole( $RoleName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationAuthorization();
        return Authorization::stageAuthorizationRole( $RoleName );
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
    public function frontendUpdateStatus()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Frontend\Update::stageStatus();
    }

    /**
     * @return void
     */
    protected function setupApplicationNavigationUpdate()
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
    public function frontendUpdateSimulation()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Frontend\Update::stageSimulation();
    }

    /**
     * @return Stage
     */
    public function frontendUpdatePerform()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigationUpdate();
        return Frontend\Update::stagePerform();
    }

    /**
     * @return Stage
     */
    public function frontendDatabaseStatus()
    {

        $this->setupModuleNavigation();
        return Frontend\Database::stageStatus();
    }

    /**
     * @return Stage
     */
    public function frontendCacheStatus()
    {

        $this->setupModuleNavigation();
        return Frontend\Cache::stageStatus();
    }

    /**
     * @return Stage
     */
    public function frontendProtocolStatus()
    {

        $this->setupModuleNavigation();
        return Frontend\Protocol::stageStatus();
    }
}
