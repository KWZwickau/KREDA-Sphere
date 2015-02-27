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
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
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
        )->setParameterDefault( 'Id', null )->setParameterDefault( 'Access', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Access/Privilege', __CLASS__.'::frontendAccessPrivilege'
        )->setParameterDefault( 'Id', null )->setParameterDefault( 'Privilege', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System/Authorization/Privilege/Right', __CLASS__.'::frontendPrivilegeRight'
        )->setParameterDefault( 'Id', null )->setParameterDefault( 'Right', null );
    }

    /**
     * @param bool $Clear
     *
     * @return Stage
     */
    public static function frontendStatus( $Clear )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageStatus( $Clear );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
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
    public static function frontendRight( $RightName )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageRight( $RightName );
    }

    /**
     * @param null|string $PrivilegeName
     *
     * @return Stage
     */
    public static function frontendPrivilege( $PrivilegeName )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stagePrivilege( $PrivilegeName );
    }

    /**
     * @param null|string $AccessName
     *
     * @return Stage
     */
    public static function frontendAccess( $AccessName )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageAccess( $AccessName );
    }

    /**
     * @param null|string $RoleName
     *
     * @return Stage
     */
    public static function frontendRole( $RoleName )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageRole( $RoleName );
    }

    /**
     * @param null|int $Id
     * @param null|int $Access
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function frontendRoleAccess( $Id, $Access, $Remove = false )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageRoleAccess( $Id, $Access, $Remove );
    }

    /**
     * @param null|int $Id
     * @param null|int $Privilege
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function frontendAccessPrivilege( $Id, $Privilege, $Remove = false )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageAccessPrivilege( $Id, $Privilege, $Remove );
    }

    /**
     * @param null|int $Id
     * @param null|int $Right
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function frontendPrivilegeRight( $Id, $Right, $Remove = false )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stagePrivilegeRight( $Id, $Right, $Remove );
    }
}
