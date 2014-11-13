<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Gatekeeper\Client\Redirect\SignIn;
use KREDA\Sphere\Application\Gatekeeper\Client\Redirect\SignOut;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\ComponentException;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository\BadOTPException;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository\MissingParameterException;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository\ReplayedOTPException;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Client
 *
 * @package KREDA\Sphere\Application\Gatekeeper
 */
class Client extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        if (self::apiIsValidUser()) {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignOut', 'Abmelden', new OffIcon()
            );
        } else {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignIn', 'Anmeldung', new LockIcon()
            );
        }

        self::buildRoute( self::$Configuration, '/', __CLASS__.'::apiMain' );
        self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn', __CLASS__.'::apiSignIn' );
        self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignOut', __CLASS__.'::apiSignOut' );

        $Route = self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::apiSignInTeacher' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Management',
            __CLASS__.'::apiSignInManagement' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::apiSignInStudent' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );

        return $Configuration;
    }

    /**
     * @return bool
     */
    public static function apiIsValidUser()
    {

        return Access::getApi()->apiSessionIsValid();
    }

    /**
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInSwitch
     */
    public function apiSignIn()
    {

        return $this->apiMain();
    }

    /**
     * @return SignInSwitch
     */
    public function apiMain()
    {

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
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @throws \Exception
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher
     */
    public function apiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        $View = new SignInTeacher();
        $Error = false;
        if (null !== $CredentialName && empty( $CredentialName )) {
            $View->setErrorEmptyName();
            $Error = true;
        }
        if (null !== $CredentialLock && empty( $CredentialLock )) {
            $View->setErrorEmptyLock();
            $Error = true;
        }
        if (null !== $CredentialKey && empty( $CredentialKey )) {
            $View->setErrorEmptyKey();
            $Error = true;
        }
        if ($Error) {
            return $View;
        } else {
            if (null !== $CredentialKey) {
                try {
                    if (Access::getApi()->apiValidateYubiKey( $CredentialKey )) {
                        if ($CredentialName == 'demo' && $CredentialLock == 'demo') {
                            $_SESSION['Gatekeeper-Valid'] = true;
                            return new SignIn();
                        } else {
                            $View->setErrorWrongName();
                            $View->setErrorWrongLock();
                            return $View;
                        }
                    }
                } catch( BadOTPException $E ) {
                    $View->setErrorWrongKey();
                    return $View;
                } catch( ReplayedOTPException $E ) {
                    $View->setErrorReplayedKey();
                    return $View;
                } catch( ComponentException $E ) {

                    throw new \Exception( 'Es ist ein Fehler bei der Anmeldung aufgetreten' );
                }
            }
            return $View;
        }
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

        $this->setupModuleNavigation();
        $View = new SignInManagement();
        $Error = false;
        if (null !== $CredentialName && empty( $CredentialName )) {
            $View->setErrorEmptyName();
            $Error = true;
        }
        if (null !== $CredentialLock && empty( $CredentialLock )) {
            $View->setErrorEmptyLock();
            $Error = true;
        }
        if (null !== $CredentialKey && empty( $CredentialKey )) {
            $View->setErrorEmptyKey();
            $Error = true;
        }
        if ($Error) {
            return $View;
        } else {
            if (null !== $CredentialKey) {
                try {
                    if (Access::getApi()->apiValidateYubiKey( $CredentialKey )) {
                        if ($CredentialName == 'demo' && $CredentialLock == 'demo') {
                            $_SESSION['Gatekeeper-Valid'] = true;
                            return new SignIn();
                        } else {
                            $View->setErrorWrongName();
                            $View->setErrorWrongLock();
                            return $View;
                        }
                    }
                } catch( BadOTPException $E ) {
                    $View->setErrorWrongKey();
                    return $View;
                } catch( ReplayedOTPException $E ) {
                    $View->setErrorReplayedKey();
                    return $View;
                } catch( MissingParameterException $E ) {
                    throw new \Exception( $E->getMessage(), $E->getCode(), $E );
                } catch( ComponentException $E ) {
                    $View->setErrorNetworkKey();
                    return $View;
                }
            }
            return $View;
        }
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent
     */
    public function apiSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->setupModuleNavigation();
        $View = new SignInStudent();
        $Error = false;
        if (null !== $CredentialName && empty( $CredentialName )) {
            $View->setErrorEmptyName();
            $Error = true;
        }
        if (null !== $CredentialLock && empty( $CredentialLock )) {
            $View->setErrorEmptyLock();
            $Error = true;
        }
        if ($Error) {
            return $View;
        } else {
            if ($CredentialName == 'demo' && $CredentialLock == 'demo') {
                $_SESSION['Gatekeeper-Valid'] = true;
                return new SignIn();
            } else {
                if (null !== $CredentialName || null !== $CredentialLock) {
                    $View->setErrorWrongName();
                    $View->setErrorWrongLock();
                }
                return $View;
            }
        }
    }

    public function apiSignOut()
    {

        $View = new SignOut();

        $_SESSION['Gatekeeper-Valid'] = false;

        return $View;
    }
}
