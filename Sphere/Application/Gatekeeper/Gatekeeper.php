<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Authentication;
use KREDA\Sphere\Application\Gatekeeper\MyAccount\MyAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
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

        self::$Configuration = $Configuration;
        if (self::serviceAccount()->checkIsValidSession()) {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/MyAccount', 'Mein Account', new PersonIcon()
            );
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignOut', 'Abmelden', new OffIcon()
            );
        } else {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignIn', 'Anmeldung', new LockIcon()
            );
        }

        self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::apiMain' );
        self::registerClientRoute( self::$Configuration, '/Sphere', __CLASS__.'::guiWelcome' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn', __CLASS__.'::guiSignIn' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignOut', __CLASS__.'::guiSignOut' );

        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount', __CLASS__.'::guiMyAccount' );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangePassword',
            __CLASS__.'::guiMyAccountChangePassword' );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialLockSafety', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangeConsumer',
            __CLASS__.'::guiMyAccountChangeConsumer' );
        $Route->setParameterDefault( 'tblConsumer', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::guiSignInTeacher' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Management',
            __CLASS__.'::guiSignInManagement' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::guiSignInStudent' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );

        return $Configuration;
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
     * @return Service\Consumer
     */
    public static function serviceConsumer()
    {

        return Consumer::getApi();
    }

    /**
     * @return Stage
     */
    public function guiSignIn()
    {

        return $this->apiMain();
    }

    /**
     * @return Stage
     */
    public function apiMain()
    {

        $this->setupModuleNavigation();
        return Authentication::guiSignInSwitch();
    }

    /**
     *
     */
    public function setupModuleNavigation()
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
     * @return Landing
     */
    public function guiWelcome()
    {

        $View = new Landing();
        $View->setTitle( 'Willkommen' );
        $View->setMessage( date( 'd.m.Y - H:i:s' ) );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public function guiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        return Authentication::guiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public function guiSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModuleNavigation();
        return Authentication::guiSignInManagement( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public function guiSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->setupModuleNavigation();
        return Authentication::guiSignInStudent( $CredentialName, $CredentialLock );
    }

    /**
     * @return Stage
     */
    public function guiSignOut()
    {

        $this->setupModuleNavigation();
        return Authentication::guiSignOut();
    }

    /**
     * @return Stage
     */
    public function guiMyAccount()
    {

        $this->setupModuleMyAccount();
        return MyAccount::guiSummary();
    }

    public function setupModuleMyAccount()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/MyAccount/ChangePassword', 'Passwort ändern', new LockIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Gatekeeper/MyAccount/ChangeConsumer', 'Mandant ändern', new HomeIcon()
        );
    }

    /**
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Stage
     */
    public function guiMyAccountChangePassword( $CredentialLock, $CredentialLockSafety )
    {

        $this->setupModuleMyAccount();
        return MyAccount::guiChangePassword( $CredentialLock, $CredentialLockSafety );
    }

    /**
     * @param integer $tblConsumer
     *
     * @return Stage
     */
    public function guiMyAccountChangeConsumer( $tblConsumer )
    {

        $this->setupModuleMyAccount();
        return MyAccount::guiChangeConsumer( $tblConsumer );
    }
}
