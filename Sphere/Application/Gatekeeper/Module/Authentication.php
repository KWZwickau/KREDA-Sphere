<?php
namespace KREDA\Sphere\Application\Gatekeeper\Module;

use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\Authentication as Frontend;
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
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        /**
         * Authentication
         */
        if (( $ValidSession = Gatekeeper::serviceAccount()->checkIsValidSession() )) {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendAuthenticationStatus' );
        } else {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendAuthenticationSignInSwitch' );
        }
        self::registerClientRoute( self::$Configuration, '/Sphere',
            __CLASS__.'::frontendAuthenticationStatus' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn',
            __CLASS__.'::frontendAuthenticationSignInSwitch' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignOut',
            __CLASS__.'::frontendAuthenticationSignOut' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::frontendAuthenticationSignInStudent' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::frontendAuthenticationSignInTeacher' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Management',
            __CLASS__.'::frontendAuthenticationSignInManagement' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/System',
            __CLASS__.'::frontendAuthenticationSignInSystem' )
            ->setParameterDefault( 'CredentialName', null )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialKey', null );
    }

    /**
     * @return Stage
     */
    public function frontendAuthenticationSignInSwitch()
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignInSwitch();
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
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
    public function frontendAuthenticationStatus()
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
    public function frontendAuthenticationSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public function frontendAuthenticationSignInSystem( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignInSystem( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public function frontendAuthenticationSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignInManagement( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public function frontendAuthenticationSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignInStudent( $CredentialName, $CredentialLock );
    }

    /**
     * @return Stage
     */
    public function frontendAuthenticationSignOut()
    {

        $this->setupModuleNavigation();
        return Frontend::stageSignOut();
    }
}
