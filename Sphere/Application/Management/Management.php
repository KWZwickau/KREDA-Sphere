<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;
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
            Module\Education::registerApplication( $Configuration );
        }
        /**
         * Observer
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/REST/PersonListInterest',
            __CLASS__.'::restPerson' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Interessent' )->getId() );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/REST/PersonListStudent',
            __CLASS__.'::restPerson' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId() );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/REST/PersonListGuardian',
            __CLASS__.'::restPerson' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )->getId() );
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
     * @return Observer
     */
    public static function observerDestroyPerson()
    {

        return Observer::initWire( new Plug( __CLASS__, __FUNCTION__ ) );
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

    public static function restPerson( $tblPersonType, $draw, $start, $length, $search )
    {

        $tblPersonType = Management::servicePerson()->entityPersonTypeById( $tblPersonType );
        $tblPerson = Management::servicePerson()->entityPersonAllByType( $tblPersonType, null, $length, $start );

//        REST:true
//        draw:13
//        columns[0][data]:Id
//        columns[0][name]:
//        columns[0][searchable]:true
//        columns[0][orderable]:true
//        columns[0][search][value]:
//        columns[0][search][regex]:false
//        columns[1][data]:FirstName
//        columns[1][name]:
//        columns[1][searchable]:true
//        columns[1][orderable]:true
//        columns[1][search][value]:
//        columns[1][search][regex]:false
//        order[0][column]:1
//        order[0][dir]:asc
//        order[1][column]:0
//        order[1][dir]:desc
//        start:20
//        length:10
//        search[value]:bernd das
//        search[regex]:false
//        _:1426261763420

        array_walk( $tblPerson, function ( TblPerson &$P ) {

            $P->Option = ( new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
                array( 'Id' => $P->getId() )
            ) )->__toString();

            $P = $P->__toArray();
        } );

        print json_encode( array(
            'draw'            => (int)$draw,
            'recordsTotal'    => Management::servicePerson()->countPersonAllByType( $tblPersonType ),
            'recordsFiltered' => Management::servicePerson()->countPersonAllByType( $tblPersonType ),
            'data'            => $tblPerson
        ) );
    }
}
