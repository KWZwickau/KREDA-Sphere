<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Address\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Redirect;
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
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Address', $this->getConsumerSuffix() );
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
     * @return bool|TblAddress[]
     */
    public function entityAddressAll()
    {

        return parent::entityAddressAll();
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
     * @return bool|TblAddressCity[]
     */
    public function entityAddressCityAll()
    {

        return parent::entityAddressCityAll();
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
    public function entityAddressStateAll()
    {

        return parent::entityAddressStateAll();
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAddressState
     */
    public function entityAddressStateByName( $Name )
    {

        return parent::entityAddressStateByName( $Name );
    }

    /**
     * @param AbstractType $Form
     * @param string       $Code
     * @param string       $Name
     * @param null|string  $District
     *
     * @return AbstractType|Redirect
     */
    public function executeCreateAddressCity( AbstractType &$Form, $Code, $Name, $District = null )
    {

        if (null === $Code
            && null === $Name
            && null === $District
        ) {
            return $Form;
        }
        $Error = false;

        if (!preg_match( '!^[0-9]{5}$!is', $Code )) {
            $Form->setError( 'Code', 'Bitte geben Sie eine fünfstellige Postleitzahl ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'Code' );
        }
        if (empty( $Name )) {
            $Form->setError( 'Name', 'Bitte geben Sie einen Namen ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'Name' );
        }

        if (!$Error) {
            $this->actionCreateAddressCity( $Code, $Name, $District );
            return new Redirect( '/Sphere/Management/Huppala', 0 );
        }
        return $Form;
    }

    /**
     * @param string $Code
     * @param string $Name
     * @param null   $District
     *
     * @return TblAddressCity
     */
    public function actionCreateAddressCity( $Code, $Name, $District = null )
    {

        return parent::actionCreateAddressCity( $Code, $Name, $District );
    }

    /**
     * @param TblAddressState $TblAddressState
     * @param TblAddressCity  $TblAddressCity
     * @param null            $StreetName
     * @param null            $StreetNumber
     * @param null            $PostOfficeBox
     *
     * @return TblAddress
     */
    public function actionCreateAddress(
        TblAddressState $TblAddressState = null,
        TblAddressCity $TblAddressCity = null,
        $StreetName = null,
        $StreetNumber = null,
        $PostOfficeBox = null
    ) {

        return parent::actionCreateAddress( $TblAddressState, $TblAddressCity, $StreetName, $StreetNumber,
            $PostOfficeBox );
    }

}
