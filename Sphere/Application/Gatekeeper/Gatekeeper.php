<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Wire\Data;
use KREDA\Sphere\Common\Wire\Observer;
use KREDA\Sphere\Common\Wire\Plug;

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
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        if (self::serviceAccount()->checkIsValidSession()) {
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

        /**
         * Observer
         */
        Gatekeeper::observerDestroyAccount()->plugWire( new Plug( __CLASS__, 'listenerDestroyAccount' ) );
        Management::observerDestroyPerson()->plugWire( new Plug( __CLASS__, 'listenerDestroyPerson' ) );
    }

    /**
     * @return Service\Account
     */
    public static function serviceAccount()
    {

        return Account::getApi();
    }

    /**
     * @return Observer
     */
    public static function observerDestroyAccount()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
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

    /**
     * @param Data $Data
     *
     * @return bool|string
     */
    public static function listenerDestroyPerson( Data $Data )
    {

        return false;

    }

    /**
     * @param Data $Data
     *
     * @return bool|string
     */
    public static function listenerDestroyAccount( Data $Data )
    {

        /**
         * Kill Session before Kill Account
         */
        $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Data->getId() );
        $tblSessionList = Gatekeeper::serviceAccount()->entitySessionAllByAccount( $tblAccount );
        if (!empty( $tblSessionList )) {
            array_walk( $tblSessionList, function ( TblAccountSession &$S ) {

                if (true !== ( $Wire = Gatekeeper::serviceAccount()->executeDestroySession( $S ) )) {
                    $S = $Wire;
                } else {
                    $S = false;
                }
            } );
            $tblSessionList = array_filter( $tblSessionList );
            if (!empty( $tblSessionList )) {
                /**
                 * Done, CRITICAL -> return wire
                 */
                $Return = new MessageDanger( 'Der Account kann nicht gelöscht werden, da der Benutzer noch offene Sessions hat' );
                $Return .= implode( (array)$tblSessionList );
                return $Return;
            }
        }
        /**
         * Done, not critical -> return true
         */
        return true;
    }

    /**
     * @return Observer
     */
    public static function observerDestroySession()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
    }
}
