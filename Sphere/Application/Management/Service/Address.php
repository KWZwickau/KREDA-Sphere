<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Address\EntityAction;

/**
 * Class Address
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Address extends EntityAction
{

    /**
     *
     */
    function __construct()
    {

        if (false !== ( $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession() )) {
            $Consumer = $tblConsumer->getDatabaseSuffix();
        } else {
            $Consumer = 'EGE';
        }
        $this->setDatabaseHandler( 'Management', 'Address', $Consumer );
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
        $tblAddressState = $this->actionCreateAddressState( 'Sachsen' );
        $tblAddressCity = $this->actionCreateAddressCity( '08056', 'Zwickau' );
        $this->actionCreateAddress( $tblAddressState, $tblAddressCity, 'Am Bahnhof', '4' );

        $tblAddressState = $this->actionCreateAddressState( 'Sachsen' );
        $tblAddressCity = $this->actionCreateAddressCity( '09456', 'Annaberg-Buchholz' );
        $this->actionCreateAddress( $tblAddressState, $tblAddressCity, 'Straße der Freundschaft', '11' );
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
