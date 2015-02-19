<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Authorization\Authorization as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class MyAccount
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Module
 */
class Authorization extends Protocol
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
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization', __CLASS__.'::frontendStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Role', __CLASS__.'::frontendRole'
        )->setParameterDefault( 'RoleName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access', __CLASS__.'::frontendAccess'
        )->setParameterDefault( 'AccessName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege', __CLASS__.'::frontendPrivilege'
        )->setParameterDefault( 'PrivilegeName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Right', __CLASS__.'::frontendRight'
        )->setParameterDefault( 'RightName', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Role/Access', __CLASS__.'::frontendRoleAccess'
        )->setParameterDefault( 'Role', null )->setParameterDefault( 'Access', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access/Privilege', __CLASS__.'::frontendAccessPrivilege'
        )->setParameterDefault( 'Access', null )->setParameterDefault( 'Privilege', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege/Right', __CLASS__.'::frontendPrivilegeRight'
        )->setParameterDefault( 'Privilege', null )->setParameterDefault( 'Right', null );
    }

    /**
     * @return Stage
     */
    public function frontendStatus()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stageStatus();
    }

    /**
     * @return void
     */
    protected function setupApplicationNavigation()
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
    public function frontendRight( $RightName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stageRight( $RightName );
    }

    /**
     * @param null|string $PrivilegeName
     *
     * @return Stage
     */
    public function frontendPrivilege( $PrivilegeName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stagePrivilege( $PrivilegeName );
    }

    /**
     * @param null|string $AccessName
     *
     * @return Stage
     */
    public function frontendAccess( $AccessName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stageAccess( $AccessName );
    }

    /**
     * @param null|string $RoleName
     *
     * @return Stage
     */
    public function frontendRole( $RoleName )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stageRole( $RoleName );
    }

    /**
     * @param null|int $Role
     * @param null|int $Access
     *
     * @return Stage
     */
    public function frontendRoleAccess( $Role, $Access )
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return Frontend::stageRoleAccess( $Role, $Access );
    }
}
