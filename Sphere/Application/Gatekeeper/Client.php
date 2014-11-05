<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Gatekeeper\Client\Entrance\SignIn;
use KREDA\Sphere\Application\Gatekeeper\Client\Entrance\SignOut;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
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
            self::buildNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignOut', 'Abmelden', new OffIcon()
            );
        } else {
            self::buildNavigationMeta( self::$Configuration,
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

    public static function apiIsValidUser()
    {

        if (isset( $_SESSION['Gatekeeper-Valid'] )) {
            return $_SESSION['Gatekeeper-Valid'];
        } else {
            return false;
        }
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

        $this->setupModule();
        $View = new Landing();
        $View->setTitle( 'Anmeldung' );
        $View->setMessage( 'Bitte wählen Sie den Typ der Anmeldung' );
        $View->setContent( new SignInSwitch() );
        return $View;

    }

    /**
     *
     */
    public function setupModule()
    {

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Teacher', 'Lehrer', new LockIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Management', 'Verwaltung', new LockIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Student', 'Schüler', new LockIcon()
        );
    }

    /**
     * @param $CredentialName
     * @param $CredentialLock
     * @param $CredentialKey
     *
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInTeacher
     */
    public function apiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModule();
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
            $_SESSION['Gatekeeper-Valid'] = true;
        }

        return new SignIn();
    }

    /**
     * @param $CredentialName
     * @param $CredentialLock
     * @param $CredentialKey
     *
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInManagement
     */
    public function apiSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModule();
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

        return $View;
        if ($Error) {
            return $View;
        } else {
            //$_SESSION['Gatekeeper-Valid'] = true;
        }

        //return new SignIn();
    }

    /**
     * @param $CredentialName
     * @param $CredentialLock
     *
     * @return \KREDA\Sphere\Application\Gatekeeper\Client\SignIn\SignInStudent
     */
    public function apiSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->setupModule();
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
            $_SESSION['Gatekeeper-Valid'] = true;
        }

        return new SignIn();
    }

    public function apiSignOut()
    {

        $View = new SignOut();

        $_SESSION['Gatekeeper-Valid'] = false;

        return $View;
    }
}
