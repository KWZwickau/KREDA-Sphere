<?php
namespace KREDA\Sphere\Application\Gatekeeper\Module;

use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication as Frontend;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Authentication
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Module
 */
class Authentication extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        /**
         * Authentication
         */
        if (( $ValidSession = Gatekeeper::serviceAccount()->checkIsValidSession() )) {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendStatus' );
        } else {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendSignInSwitch' );
        }
        self::registerClientRoute( self::$Configuration, '/Sphere',
            __CLASS__.'::frontendStatus' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn',
            __CLASS__.'::frontendSignInSwitch' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignOut',
            __CLASS__.'::frontendSignOut' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::frontendSignInStudent' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::frontendSignInTeacher' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Management',
            __CLASS__.'::frontendSignInManagement' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/System',
            __CLASS__.'::frontendSignInSystem' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
    }

    /**
     * @return Stage
     */
    public static function frontendSignInSwitch()
    {

        self::setupModuleNavigation();
        return Frontend::stageSignInSwitch();
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Student', 'Schüler', new LockIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Teacher', 'Lehrer', new LockIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Management', 'Verwaltung', new LockIcon()
        );
    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {

        return Frontend::stageStatus();
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function frontendSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        self::setupModuleNavigation();
        return Frontend::stageSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function frontendSignInSystem( $CredentialName, $CredentialLock, $CredentialKey )
    {

        self::setupModuleNavigation();
        return Frontend::stageSignInSystem( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function frontendSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        self::setupModuleNavigation();
        return Frontend::stageSignInManagement( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public static function frontendSignInStudent( $CredentialName, $CredentialLock )
    {

        self::setupModuleNavigation();
        return Frontend::stageSignInStudent( $CredentialName, $CredentialLock );
    }

    /**
     * @return Stage
     */
    public static function frontendSignOut()
    {

        self::setupModuleNavigation();
        return Frontend::stageSignOut();
    }
}
