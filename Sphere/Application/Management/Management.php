<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;
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
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Interessent' )->getId() );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/REST/PersonListStudent',
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId() );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/REST/PersonListGuardian',
            __CLASS__.'::restPersonListByType' )
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

    /**
     * @param int $tblPersonType
     */
    public static function restPersonListByType( $tblPersonType )
    {

        $tblPersonType = Management::servicePerson()->entityPersonTypeById( $tblPersonType );
        print Management::servicePerson()->tablePersonAllByType( $tblPersonType );
    }
}
