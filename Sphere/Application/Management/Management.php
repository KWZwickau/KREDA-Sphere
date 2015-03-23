<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Wire\Data;
use KREDA\Sphere\Common\Wire\Observer;
use KREDA\Sphere\Common\Wire\Plug;

/**
 * Class Management
 *
 * @package KREDA\Sphere\Application\Management
 */
class Management extends Module\Education
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::setupApplicationAccess( 'Management' );
        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( 'Application:Management' )) {

            self::registerClientRoute( self::$Configuration, '/Sphere/Management', __CLASS__.'::frontendManagement' );
            self::addClientNavigationMain( self::$Configuration, '/Sphere/Management', 'Verwaltung',
                new CogWheelsIcon() );

            Module\Common::registerApplication( $Configuration );
            if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Token' )) {
                Module\Token::registerApplication( $Configuration );
            }
            if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Account' )) {
                Module\Account::registerApplication( $Configuration );
            }
            Module\Person::registerApplication( $Configuration );
            Module\Relationship::registerApplication( $Configuration );
            Module\Education::registerApplication( $Configuration );
        }
        /**
         * Observer
         */
        Management::observerDestroyPerson()->plugWire( new Plug( __CLASS__, 'listenerDestroyPerson' ) );
    }

    /**
     * @return Observer
     */
    public static function observerDestroyPerson()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
    }

    /**
     * @return Observer
     */
    public static function observerDestroyRelationship()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Education
     */
    public static function serviceEducation( TblConsumer $tblConsumer = null )
    {

        return Service\Education::getApi( $tblConsumer );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Address
     */
    public static function serviceAddress( TblConsumer $tblConsumer = null )
    {

        return Service\Address::getApi( $tblConsumer );
    }

    /**
     * @param Data $Data
     *
     * @return bool|string
     */
    public static function listenerDestroyPerson( Data $Data )
    {

        /**
         * Kill Relationship before Kill Person
         */
        $tblPerson = Management::servicePerson()->entityPersonById( $Data->getId() );
        $tblRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );
        if (!empty( $tblRelationshipList )) {
            array_walk( $tblRelationshipList, function ( TblPersonRelationshipList &$R ) {

                if (true !== ( $Effect = Management::servicePerson()->executeDestroyRelationship( $R ) )) {
                    $R = $Effect;
                } else {
                    $R = false;
                }
            } );
            $tblRelationshipList = array_filter( $tblRelationshipList );
            if (!empty( $tblRelationshipList )) {
                /**
                 * Done, CRITICAL -> return wire
                 */
                $Return = new MessageDanger( 'Die Person kann nicht gelöscht werden, da noch Beziehungen zu anderen Personen existieren' );
                $Return .= implode( (array)$tblRelationshipList );
                return $Return;
            }
        }
        /**
         * Done, not critical -> return true
         */
        return true;
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Person
     */
    public static function servicePerson( TblConsumer $tblConsumer = null )
    {

        return Service\Person::getApi( $tblConsumer );
    }

    /**
     * @return Stage
     */
    public static function frontendManagement()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Verwaltung' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }
}
