<?php
namespace KREDA\Sphere\Application\Gatekeeper\Module;

use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount as Frontend;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class MyAccount
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Module
 */
class MyAccount extends Authentication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/MyAccount', 'Mein Account', new PersonIcon()
            );
        }
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount',
            __CLASS__.'::frontendStatus' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangePassword',
            __CLASS__.'::frontendChangePassword' )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialLockSafety', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangeConsumer',
            __CLASS__.'::frontendChangeConsumer' )
            ->setParameterDefault( 'serviceGatekeeperConsumer', null );
    }

    /**
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Stage
     */
    public static function frontendChangePassword( $CredentialLock, $CredentialLockSafety )
    {

        self::setupModuleNavigation();
        return Frontend::stageChangePassword( $CredentialLock, $CredentialLockSafety );
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
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
     * @param int $serviceGatekeeperConsumer
     *
     * @return Stage
     */
    public static function frontendChangeConsumer( $serviceGatekeeperConsumer )
    {

        self::setupModuleNavigation();
        return Frontend::stageChangeConsumer( $serviceGatekeeperConsumer );
    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {

        self::setupModuleNavigation();
        return Frontend::stageStatus();
    }
}
