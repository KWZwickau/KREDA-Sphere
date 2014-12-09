<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Error;
use KREDA\Sphere\Application\Gatekeeper\Authentication\Redirect;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Gatekeeper
 *
 * @package KREDA\Sphere\Application\Gatekeeper
 */
class Gatekeeper extends AbstractApplication
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
        if (self::checkIsValidSession()) {
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
    public static function checkIsValidSession()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return self::serviceAccount()->checkIsValidSession();
    }

    /**
     * @return Service\Account
     */
    public static function serviceAccount()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return Account::getApi();
    }

    /**
     * @return Service\Token
     */
    public static function serviceToken()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return Token::getApi();
    }

    /**
     * @return Service\Access
     */
    public static function serviceAccess()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return Access::getApi();
    }

    /**
     * @return Service\Consumer
     */
    public static function serviceConsumer()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return Consumer::getApi();
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
     * @return \KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInTeacher
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
     * @param Error  $View
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Error|Redirect
     */
    private function apiSignInWithCredentialKey( Error &$View, $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

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
                return new Redirect( 'Anmelden', '/Sphere', '', 'Sie werden am System angemeldet...', 1 );
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
     * @return \KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInManagement
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
     * @return Redirect|SignInStudent
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
                return new Redirect( 'Anmelden', '/Sphere', '', 'Sie werden am System angemeldet...', 1 );
                break;
            }
        }
        return $View;
    }

    /**
     * @return Redirect
     */
    public function apiSignOut()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new Redirect( 'Abmelden', '/Sphere/Gatekeeper/SignIn', '', 'Sie werden vom System abgemeldet...', 1 );
        $this->serviceAccount()->apiSignOut();
        return $View;
    }
}
