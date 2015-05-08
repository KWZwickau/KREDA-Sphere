<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
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
            Module\Period::registerApplication( $Configuration );
            Module\Token::registerApplication( $Configuration );
            Module\Account::registerApplication( $Configuration );
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
     * @return Service\Education
     */
    public static function serviceEducation()
    {

        return Service\Education::getApi();
    }

    /**
     * @return Service\Address
     */
    public static function serviceAddress()
    {

        return Service\Address::getApi();
    }

    /**
     * @return Service\Student
     */
    public static function serviceStudent()
    {

        return Service\Student::getApi();
    }

    /**
     * @return Service\Course
     */
    public static function serviceCourse()
    {

        return Service\Course::getApi();
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
                $Return = new Danger( 'Die Person kann nicht gelöscht werden, da noch Beziehungen zu anderen Personen existieren' );
                $Return .= implode( (array)$tblRelationshipList );
                return $Return;
            }
        }
        /**
         * Clear AddressList before Kill Person
         */
        $tblPerson = Management::servicePerson()->entityPersonById( $Data->getId() );
        $tblAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );
        if (!empty( $tblAddressList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblAddressList, function ( TblAddress &$tblAddress, $Index, TblPerson $tblPerson ) {

                if (true !== ( $Effect = Management::servicePerson()->executeRemoveAddress( $tblPerson->getId(),
                        $tblAddress->getId() ) )
                ) {
                    $tblAddress = $Effect;
                } else {
                    $tblAddress = false;
                }
            }, $tblPerson );
            $tblAddressList = array_filter( $tblAddressList );
            if (!empty( $tblAddressList )) {
                /**
                 * Done, CRITICAL -> return wire
                 */
                $Return = new Danger( 'Die Person kann nicht gelöscht werden, da noch Adressen existieren' );
                return $Return;
            }
        }
        /**
         * Done, not critical -> return true
         */
        return true;
    }

    /**
     * @return Service\Person
     */
    public static function servicePerson()
    {

        return Service\Person::getApi();
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
