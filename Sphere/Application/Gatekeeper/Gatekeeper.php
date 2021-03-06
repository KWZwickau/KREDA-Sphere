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
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
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
        Gatekeeper::observerDestroyToken()->plugWire( new Plug( __CLASS__, 'listenerDestroyToken' ) );
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
     * @return Observer
     */
    public static function observerDestroyToken()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
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
    public static function listenerDestroyToken( Data $Data )
    {

        $tblToken = Gatekeeper::serviceToken()->entityTokenById( $Data->getId() );
        $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByToken( $tblToken );
        $Result = array();
        if (!empty( $tblAccountList )) {
            foreach ((array)$tblAccountList as $tblAccount) {
                if (true !== ( $Effect = Gatekeeper::serviceAccount()->executeDestroyAccount( $tblAccount ) )) {
                    $Result[] = $Effect;
                };
            }
        }
        if (empty( $Result )) {
            return true;
        } else {
            return implode( $Result );
        }
    }

    /**
     * @return Service\Token
     */
    public static function serviceToken()
    {

        return Token::getApi();
    }

    /**
     * @param Data $Data
     *
     * @return bool|string
     */
    public static function listenerDestroyPerson( Data $Data )
    {

        $tblPerson = Management::servicePerson()->entityPersonById( $Data->getId() );
        $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByPerson( $tblPerson );
        $Result = array();
        if (!empty( $tblAccountList )) {
            foreach ((array)$tblAccountList as $tblAccount) {
                if (true !== ( $Effect = Gatekeeper::serviceAccount()->executeDestroyAccount( $tblAccount ) )) {
                    $Result[] = $Effect;
                };
            }
        }
        if (empty( $Result )) {
            return true;
        } else {
            return implode( $Result );
        }
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

                if (true !== ( $Effect = Gatekeeper::serviceAccount()->executeDestroySession( $S ) )) {
                    $S = $Effect;
                } else {
                    $S = false;
                }
            } );
            $tblSessionList = array_filter( $tblSessionList );
            if (!empty( $tblSessionList )) {
                /**
                 * Done, CRITICAL -> return wire
                 */
                $Return = new Danger( 'Der Account kann nicht gelöscht werden, da der Benutzer noch offene Sessions hat' );
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

    /**
     * @return Observer
     */
    public static function observerDestroyConsumer()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
    }
}
