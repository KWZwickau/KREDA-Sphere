<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Address\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Address
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Address extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Management', 'Address', $this->getConsumerSuffix( $tblConsumer ) );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        $this->actionCreateAddressState( 'Baden-Württemberg' );
        $this->actionCreateAddressState( 'Bremen' );
        $this->actionCreateAddressState( 'Niedersachsen' );
        $this->actionCreateAddressState( 'Sachsen' );
        $this->actionCreateAddressState( 'Bayern' );
        $this->actionCreateAddressState( 'Hamburg' );
        $this->actionCreateAddressState( 'Nordrhein-Westfalen' );
        $this->actionCreateAddressState( 'Sachsen-Anhalt' );
        $this->actionCreateAddressState( 'Berlin' );
        $this->actionCreateAddressState( 'Hessen' );
        $this->actionCreateAddressState( 'Rheinland-Pfalz' );
        $this->actionCreateAddressState( 'Schleswig-Holstein' );
        $this->actionCreateAddressState( 'Brandenburg' );
        $this->actionCreateAddressState( 'Mecklenburg-Vorpommern' );
        $this->actionCreateAddressState( 'Saarland' );
        $this->actionCreateAddressState( 'Thüringen' );
        /**
         * Support-Center
         */
//        $tblAddressState = $this->actionCreateAddressState( 'Sachsen' );
//        $tblAddressCity = $this->actionCreateAddressCity( '08056', 'Zwickau' );
//        $tblAddress = $this->actionCreateAddress( $tblAddressState, $tblAddressCity, 'Am Bahnhof', '4' );
//
//        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerByName( 'System' );
//        Gatekeeper::serviceConsumer()->executeChangeAddress( $tblAddress, $tblConsumer );

        $tblAddressState = $this->actionCreateAddressState( 'Sachsen' );
        $tblAddressCity = $this->actionCreateAddressCity( '09456', 'Annaberg-Buchholz' );
        $tblAddress = $this->actionCreateAddress( $tblAddressState, $tblAddressCity, 'Straße der Freundschaft', '11' );

        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySuffix( 'EGE' );
        Gatekeeper::serviceConsumer()->executeChangeAddress( $tblAddress, $tblConsumer );

        $tblAddressState = $this->actionCreateAddressState( 'Sachsen' );
        $tblAddressCity = $this->actionCreateAddressCity( '09130', 'Chemnitz' );
        $tblAddress = $this->actionCreateAddress( $tblAddressState, $tblAddressCity, 'Tschaikowskistraße', '49' );

        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySuffix( 'ESZC' );
        Gatekeeper::serviceConsumer()->executeChangeAddress( $tblAddress, $tblConsumer );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddress
     */
    public function entityAddressById( $Id )
    {

        return parent::entityAddressById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressCity
     */
    public function entityAddressCityById( $Id )
    {

        return parent::entityAddressCityById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressState
     */
    public function entityAddressStateById( $Id )
    {

        return parent::entityAddressStateById( $Id );
    }

    /**
     * @return bool|TblAddressState[]
     */
    public function entityAddressState()
    {

        return parent::entityAddressState();
    }
}
