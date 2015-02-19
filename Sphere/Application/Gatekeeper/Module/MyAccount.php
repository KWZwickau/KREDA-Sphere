<?php
namespace KREDA\Sphere\Application\Gatekeeper\Module;

use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\MyAccount as Frontend;
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
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        if (( $ValidSession = Gatekeeper::serviceAccount()->checkIsValidSession() )) {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/MyAccount', 'Mein Account', new PersonIcon()
            );
        }
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount',
            __CLASS__.'::frontendMyAccountSummary' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangePassword',
            __CLASS__.'::frontendMyAccountChangePassword' )
            ->setParameterDefault( 'CredentialLock', null )
            ->setParameterDefault( 'CredentialLockSafety', null );
        self::registerClientRoute( self::$Configuration, '/Sphere/Gatekeeper/MyAccount/ChangeConsumer',
            __CLASS__.'::frontendMyAccountChangeConsumer' )
            ->setParameterDefault( 'serviceGatekeeper_Consumer', null );

    }

    /**
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Stage
     */
    public function frontendMyAccountChangePassword( $CredentialLock, $CredentialLockSafety )
    {

        $this->setupModuleNavigation();
        return Frontend::stageChangePassword( $CredentialLock, $CredentialLockSafety );
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
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
     * @param int $serviceGatekeeper_Consumer
     *
     * @return Stage
     */
    public function frontendMyAccountChangeConsumer( $serviceGatekeeper_Consumer )
    {

        $this->setupModuleNavigation();
        return Frontend::stageChangeConsumer( $serviceGatekeeper_Consumer );
    }

    /**
     * @return Stage
     */
    public function frontendMyAccountSummary()
    {

        $this->setupModuleNavigation();
        return Frontend::stageStatus();
    }
}
