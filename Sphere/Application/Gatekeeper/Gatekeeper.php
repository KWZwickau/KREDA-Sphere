<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\Authentication;
use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\MyAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
        /**
         * Navigation
         */
        if (( $ValidSession = self::serviceAccount()->checkIsValidSession() )) {
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
        /**
         * Authentication
         */
        if ($ValidSession) {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendAuthenticationWelcome' );
        } else {
            self::registerClientRoute( self::$Configuration, '/', __CLASS__.'::frontendAuthenticationSignInSwitch' );
        }
        self::registerClientRoute( self::$Configuration, '/Sphere',
            __CLASS__.'::frontendAuthenticationWelcome' );
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
        /**
         * MyAccount
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount',
            __CLASS__.'::frontendMyAccountSummary' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangePassword',
            __CLASS__.'::frontendMyAccountChangePassword' )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialLockSafety', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangeConsumer',
            __CLASS__.'::frontendMyAccount_ChangeConsumer' )
            ->setParameterDefault( 'tblConsumer', null );

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
     * @return Service\Consumer
     */
    public static function serviceConsumer()
    {

        return Consumer::getApi();
    }

    /**
     * @return Stage
     */
    public function frontendAuthenticationSignInSwitch()
    {

        $this->setupModuleNavigation();
        return Authentication::stageSignInSwitch();
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
     * @return Stage
     */
    public function frontendAuthenticationWelcome()
    {

        return Authentication::stageWelcome();
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
        return Authentication::stageSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey );
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
        return Authentication::stageSignInSystem( $CredentialName, $CredentialLock, $CredentialKey );
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
        return Authentication::stageSignInManagement( $CredentialName, $CredentialLock, $CredentialKey );
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
        return Authentication::stageSignInStudent( $CredentialName, $CredentialLock );
    }

    /**
     * @return Stage
     */
    public function frontendAuthenticationSignOut()
    {

        $this->setupModuleNavigation();
        return Authentication::stageSignOut();
    }

    /**
     * @return Stage
     */
    public function frontendMyAccountSummary()
    {

        $this->setupModuleMyAccount();
        return MyAccount::stageSummary();
    }

    public function setupModuleMyAccount()
    {

        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Gatekeeper/MyAccount/ChangePassword' )) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/Gatekeeper/MyAccount/ChangePassword', 'Passwort ändern', new LockIcon()
            );
        }
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Gatekeeper/MyAccount/ChangeConsumer' )) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/Gatekeeper/MyAccount/ChangeConsumer', 'Mandant ändern', new LockIcon()
            );
        }
    }

    /**
     * @return Service\Access
     */
    public static function serviceAccess()
    {

        return Access::getApi();
    }

    /**
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Stage
     */
    public function frontendMyAccountChangePassword( $CredentialLock, $CredentialLockSafety )
    {

        $this->setupModuleMyAccount();
        return MyAccount::stageChangePassword( $CredentialLock, $CredentialLockSafety );
    }
}
