<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\Management\Service\Person\EntityAction;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Frontend\Redirect;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Person extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Management', 'Person', $this->getConsumerSuffix( $tblConsumer ) );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        /**
         * Salutation
         */
        $this->actionCreateSalutation( 'Herr' );
        $this->actionCreateSalutation( 'Frau' );
        /**
         * Gender
         */
        $this->actionCreateGender( 'Männlich' );
        $this->actionCreateGender( 'Weiblich' );
        /**
         * Type
         */
        $this->actionCreateType( 'Interessent' );
        $this->actionCreateType( 'Schüler' );
        $this->actionCreateType( 'Sorgeberechtigter' );
        /**
         * Relationship Type
         */
        $this->actionCreateRelationshipType( 'Sorgeberechtigt' );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPerson
     */
    public function entityPersonById( $Id )
    {

        return parent::entityPersonById( $Id );
    }

    /**
     * @return bool|TblPerson[]
     */
    public function entityPersonAll()
    {

        return parent::entityPersonAll();
    }

    /**
     * @return bool|TblPersonGender[]
     */
    public function entityPersonGenderAll()
    {

        return parent::entityPersonGenderAll();
    }

    /**
     * @return bool|TblPersonSalutation[]
     */
    public function entityPersonSalutationAll()
    {

        return parent::entityPersonSalutationAll();
    }

    /**
     * @return bool|TblPersonType[]
     */
    public function entityPersonTypeAll()
    {

        return parent::entityPersonTypeAll();
    }

    /**
     * @param AbstractForm $View
     *
     * @param  array $PersonName
     * @param  array $BirthDetail
     * @param  array $PersonInformation
     * @param  array $Button
     *
     * @return AbstractForm
     */
    public function executeCreatePerson(
        AbstractForm &$View = null,
        $PersonName,
        $PersonInformation,
        $BirthDetail,
        $Button
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $PersonName
            && null === $BirthDetail
            && null === $PersonInformation
            && null === $Button
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $PersonName['Salutation'] ) && !empty( $PersonName['Salutation'] )) {
            $tblPersonSalutation = Management::servicePerson()->entityPersonSalutationById( $PersonName['Salutation'] );
            if (!$tblPersonSalutation) {
                $View->setError( 'PersonName[Salutation]', 'Bitte wählen Sie eine gültige Anrede' );
                $Error = true;
            }
        } else {
            $View->setError( 'PersonName[Salutation]', 'Bitte wählen Sie eine gültige Anrede' );
            $Error = true;
        }

        if (isset( $PersonName['First'] ) && empty( $PersonName['First'] )) {
            $View->setError( 'PersonName[First]', 'Bitte geben Sie einen Vornamen an' );
            $Error = true;
        }
        if (isset( $PersonName['Last'] ) && empty( $PersonName['Last'] )) {
            $View->setError( 'PersonName[Last]', 'Bitte geben Sie einen Nachnamen an' );
            $Error = true;
        }

        if (isset( $BirthDetail['Gender'] ) && !empty( $BirthDetail['Gender'] )) {
            $tblPersonGender = Management::servicePerson()->entityPersonGenderById( $BirthDetail['Gender'] );
            if (!$tblPersonGender) {
                $View->setError( 'BirthDetail[Gender]', 'Bitte wählen Sie ein gültiges Geschlecht' );
                $Error = true;
            }
        } else {
            $View->setError( 'BirthDetail[Gender]', 'Bitte wählen Sie ein gültiges Geschlecht' );
            $Error = true;
        }

        if (isset( $BirthDetail['Date'] ) && empty( $BirthDetail['Date'] )) {
            $View->setError( 'BirthDetail[Date]', 'Bitte geben Sie ein Gebursdatum ein' );
            $Error = true;
        }

        if (isset( $PersonInformation['Nationality'] ) && empty( $PersonInformation['Nationality'] )) {
            $View->setError( 'PersonInformation[Nationality]', 'Bitte geben Sie eine Staatsangehörigkeit ein' );
            $Error = true;
        }

        if (isset( $PersonInformation['Type'] ) && !empty( $PersonInformation['Type'] )) {
            $tblPersonType = Management::servicePerson()->entityPersonTypeById( $PersonInformation['Type'] );
            if (!$tblPersonType) {
                $View->setError( 'PersonInformation[Type]', 'Bitte wählen Sie einen gültigen Typ' );
                $Error = true;
            }
        } else {
            $View->setError( 'PersonInformation[Type]', 'Bitte wählen Sie einen gültigen Typ' );
            $Error = true;
        }

        if (!$Error) {
            $Entity = $this->actionCreatePerson(
                $PersonName['First'], $PersonName['Middle'], $PersonName['Last'],
                $BirthDetail['Date'], $BirthDetail['Place'],
                $PersonInformation['Nationality'],
                $tblPersonSalutation,
                $tblPersonGender,
                $tblPersonType
            );
            if ($Button['Submit'] == 'Anlegen') {
                return new MessageSuccess( 'Die Person wurde erfolgreich angelegt' )
                .new Redirect( '/Sphere/Management/Person/Create', 0 );
            } else {
                return new MessageSuccess( 'Der Person wurde erfolgreich angelegt' )
                .new Redirect( '/Sphere/Management/Person/Edit', 5, array( 'Id' => $Entity->getId() ) );
            }
        } else {

        }
        return $View;
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonSalutation
     */
    public function entityPersonSalutationById( $Id )
    {

        return parent::entityPersonSalutationById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPersonGender
     */
    public function entityPersonGenderById( $Id )
    {

        return parent::entityPersonGenderById( $Id );
    }

    /**
     *
     * @param integer $Id
     *
     * @return bool|TblPersonType
     */
    public function entityPersonTypeById( $Id )
    {

        return parent::entityPersonTypeById( $Id );
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return bool|TblPerson[]
     */
    public function entityPersonAllByType( TblPersonType $tblPersonType )
    {

        return parent::entityPersonAllByType( $tblPersonType );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblPersonType
     */
    public function entityPersonTypeByName( $Name )
    {

        return parent::entityPersonTypeByName( $Name );
    }

}
