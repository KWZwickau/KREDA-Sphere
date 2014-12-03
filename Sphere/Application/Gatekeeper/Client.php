<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Client\Redirect\SignIn;
use KREDA\Sphere\Application\Gatekeeper\Client\Redirect\SignOut;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Client\SignInError;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Client
 *
 * @package KREDA\Sphere\Application\Gatekeeper
 */
class Client extends AbstractApplication
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
        if (self::apiIsValidSession()) {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignOut', 'Abmelden', new OffIcon()
            );
        } else {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignIn', 'Anmeldung', new LockIcon()
            );
        }

        self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::apiMain' );
        self::registerClientRoute( self::$Configuration, '/Sphere', __CLASS__.'::apiWelcome' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn', __CLASS__.'::apiSignIn' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignOut', __CLASS__.'::apiSignOut' );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::apiSignInTeacher' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Management',
            __CLASS__.'::apiSignInManagement' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::apiSignInStudent' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );

        return $Configuration;
    }

    /**
     * @return bool
     */
    public static function apiIsValidSession()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return self::serviceAccount()->apiIsValidSession();
    }

    /**
     * @return Service\Account
     */
    public static function serviceAccount()
    {

        return Account::getApi();
    }

    /**
     * @return Service\Token
     */
    public static function serviceToken()
    {

        return Token::getApi();
    }

    /**
     * @return Service\Access
     */
    public static function serviceAccess()
    {

        return Access::getApi();
    }

    /**
     * @return SignInSwitch
     */
    public function apiSignIn()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->apiMain();
    }

    /**
     * @return SignInSwitch
     */
    public function apiMain()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Anmeldung' );
        $View->setMessage( 'Bitte wählen Sie den Typ der Anmeldung' );
        $View->setContent( new SignInSwitch() );
        return $View;
    }

    /**
     *
     */
    public function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Teacher', 'Lehrer', new LockIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Management', 'Verwaltung', new LockIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Student', 'Schüler', new LockIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiWelcome()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new Landing();
        $View->setTitle( 'Willkommen' );
        $View->setMessage( '' );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @throws \Exception
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher
     */
    public function apiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        return $this->apiSignInWithCredentialKey(
            new SignInTeacher(), $CredentialName, $CredentialLock, $CredentialKey
        );
    }

    /**
     * @param SignInError $View
     * @param string      $CredentialName
     * @param string      $CredentialLock
     * @param string      $CredentialKey
     *
     * @return SignIn|SignInError
     */
    private function apiSignInWithCredentialKey( SignInError &$View, $CredentialName, $CredentialLock, $CredentialKey )
    {

        switch ($this->serviceAccount()->apiSignIn( $CredentialName, $CredentialLock, $CredentialKey )) {
            case Account::API_SIGN_IN_ERROR_CREDENTIAL:
            case Account::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setErrorEmptyName();
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setErrorWrongName();
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setErrorEmptyLock();
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setErrorWrongLock();
                }
                break;
            }
            case Account::API_SIGN_IN_ERROR_TOKEN: {
                $View->setErrorWrongKey();
                break;
            }
            case Account::API_SIGN_IN_SUCCESS: {
                return new SignIn();
                break;
            }
        }
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @throws \Exception
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInManagement
     */
    public function apiSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        return $this->apiSignInWithCredentialKey(
            new SignInManagement(), $CredentialName, $CredentialLock, $CredentialKey
        );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent
     */
    public function apiSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $View = new SignInStudent();
        switch ($this->serviceAccount()->apiSignIn( $CredentialName, $CredentialLock )) {
            case Account::API_SIGN_IN_ERROR_CREDENTIAL:
            case Account::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setErrorEmptyName();
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setErrorWrongName();
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setErrorEmptyLock();
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setErrorWrongLock();
                }
                break;
            }
            case Account::API_SIGN_IN_SUCCESS: {
                return new SignIn();
                break;
            }
        }
        return $View;
    }

    /**
     * @return SignOut
     */
    public function apiSignOut()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new SignOut();
        $this->serviceAccount()->apiSignOut();
        return $View;
    }
}
