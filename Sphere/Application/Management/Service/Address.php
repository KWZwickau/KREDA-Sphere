<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Address\EntityAction;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use MOC\V\Core\HttpKernel\HttpKernel;

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
     * @param AbstractType $Form
     * @param int|array    $State
     * @param array        $City
     * @param array        $Street
     *
     * @param TblPerson    $tblPerson
     *
     * @return AbstractType|Redirect
     */
    public function executeCreatePersonAddress( AbstractType &$Form, $State, $City, $Street, TblPerson $tblPerson )
    {

        if (null === $State
            && null === $City
            && null === $Street
        ) {
            return $Form;
        }
        $Error = false;

        if (is_numeric( $State )) {
            $State = array( 'Name' => Management::serviceAddress()->entityAddressStateById( $State )->getName() );
        }

        if (!preg_match( '!^[0-9]{5}$!is', $City['Code'] )) {
            $Form->setError( 'City[Code]', 'Bitte geben Sie eine fünfstellige Postleitzahl ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'City[Code]' );
        }
        if (empty( $City['Name'] )) {
            $Form->setError( 'City[Name]', 'Bitte geben Sie einen Namen ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'City[Name]' );
        }

        if (empty( $Street['Name'] )) {
            $Form->setError( 'Street[Name]', 'Bitte geben Sie einen Namen ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'Street[Name]' );
        }

        if (!isset( $City['District'] ) || empty( $City['District'] )) {
            $City['District'] = null;
        }
        if (!isset( $Street['Box'] ) || empty( $Street['Box'] )) {
            $Street['Box'] = null;
        }

        if (!$Error) {

            $tblState = $this->actionCreateAddressState( $State['Name'] );
            $tblCity = $this->actionCreateAddressCity( $City['Code'], $City['Name'], $City['District'] );
            $tblAddress = $this->actionCreateAddress( $tblState, $tblCity, $Street['Name'], $Street['Number'],
                $Street['Box'] );
            if (null !== $tblPerson) {
                Management::servicePerson()->executeAddAddress( $tblPerson->getId(), $tblAddress->getId() );
            }
            return new Success( 'Adresse erfolgreich angelegt' ).new Redirect( HttpKernel::getRequest()->getUrl(), 1 );
        }
        return $Form;
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
     * @param TblAddressState $TblAddressState
     * @param TblAddressCity  $TblAddressCity
     * @param null|string     $StreetName
     * @param null|string     $StreetNumber
     * @param null|string     $PostOfficeBox
     *
     * @return TblAddress
     */
    public function importCreateAddress(
        TblAddressState $TblAddressState,
        TblAddressCity $TblAddressCity,
        $StreetName = null,
        $StreetNumber = null,
        $PostOfficeBox = null
    ) {

        if (!null == $StreetName && !null == $StreetNumber) {
            $PostOfficeBox = null;
        }

        if (!null == $PostOfficeBox) {
            $StreetName = null;
            $StreetNumber = null;
        }

        return parent::actionCreateAddress(
            $TblAddressState, $TblAddressCity, $StreetName, $StreetNumber, $PostOfficeBox
        );
    }

    /**
     * @param AbstractType $Form
     * @param int|array    $State
     * @param array        $City
     * @param array        $Street
     *
     * @param TblCompany    $tblCompany
     *
     * @return AbstractType|Redirect
     */
    public function executeCreateCompanyAddress( AbstractType &$Form, $State, $City, $Street, TblCompany $tblCompany )
    {

        if (null === $State
            && null === $City
            && null === $Street
        ) {
            return $Form;
        }
        $Error = false;

        if (is_numeric( $State )) {
            $State = array( 'Name' => Management::serviceAddress()->entityAddressStateById( $State )->getName() );
        }

        if (!preg_match( '!^[0-9]{5}$!is', $City['Code'] )) {
            $Form->setError( 'City[Code]', 'Bitte geben Sie eine fünfstellige Postleitzahl ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'City[Code]' );
        }
        if (empty( $City['Name'] )) {
            $Form->setError( 'City[Name]', 'Bitte geben Sie einen Namen ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'City[Name]' );
        }

        if (empty( $Street['Name'] )) {
            $Form->setError( 'Street[Name]', 'Bitte geben Sie einen Namen ein' );
            $Error = true;
        } else {
            $Form->setSuccess( 'Street[Name]' );
        }

        if (!isset( $City['District'] ) || empty( $City['District'] )) {
            $City['District'] = null;
        }
        if (!isset( $Street['Box'] ) || empty( $Street['Box'] )) {
            $Street['Box'] = null;
        }

        if (!$Error) {

            $tblState = $this->actionCreateAddressState( $State['Name'] );
            $tblCity = $this->actionCreateAddressCity( $City['Code'], $City['Name'], $City['District'] );
            $tblAddress = $this->actionCreateAddress( $tblState, $tblCity, $Street['Name'], $Street['Number'],
                $Street['Box'] );

            if (null !== $tblCompany)
            {
                $Form  .= Management::serviceCompany()->executeAddCompanyAddress( $tblCompany, $tblAddress );
            }
//            return new Success( 'Adresse erfolgreich angelegt' )
//                .new Redirect( '/Sphere/Management/Company/Edit', 1, array('Id' => $tblCompany->getId()) );
        }
        return $Form;
    }

}
