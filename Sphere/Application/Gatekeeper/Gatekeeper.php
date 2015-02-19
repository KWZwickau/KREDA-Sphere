<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Gatekeeper
 *
 * @package KREDA\Sphere\Application\Gatekeeper
 */
class Gatekeeper extends Module\MyAccount
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
                '/Sphere/Gatekeeper/SignOut', 'Abmelden', new OffIcon()
            );
        } else {
            self::addClientNavigationMeta( self::$Configuration,
                '/Sphere/Gatekeeper/SignIn', 'Anmeldung', new LockIcon()
            );
        }

        Module\Authentication::registerApplication( $Configuration );
        Module\MyAccount::registerApplication( $Configuration );

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
     * @return Service\Access
     */
    public static function serviceAccess()
    {

        return Access::getApi();
    }
}
