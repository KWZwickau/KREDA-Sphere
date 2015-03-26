<?php
namespace KREDA\Sphere\Application\Management\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Application\Management\Service\Person\EntityAction;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Frontend\Redirect;
use KREDA\Sphere\Common\Wire\Data;
use KREDA\Sphere\Common\Wire\Effect;

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
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Person', $this->getConsumerSuffix() );
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
     * @return array|bool
     */
    public function  listPersonNationality()
    {

        return parent::listPersonNationality();
    }

    /**
     * @return array|bool
     */
    public function  listPersonBirthplace()
    {

        return parent::listPersonBirthplace();
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
     * @param TblPerson    $tblPerson
     * @param array        $PersonName
     * @param array        $PersonInformation
     * @param array        $BirthDetail
     *
     * @return AbstractForm
     */
    public function executeChangePerson(
        AbstractForm &$View = null,
        TblPerson $tblPerson,
        $PersonName,
        $PersonInformation,
        $BirthDetail
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $PersonName
            && null === $BirthDetail
            && null === $PersonInformation
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
            if ($this->actionChangePerson(
                $tblPerson, $PersonName['Title'], $PersonName['First'], $PersonName['Middle'], $PersonName['Last'],
                $BirthDetail['Date'],
                $BirthDetail['Place'], $PersonInformation['Nationality'], $tblPersonSalutation, $tblPersonGender,
                $tblPersonType
            )
            ) {
                $View .= new MessageSuccess( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Management/Person/Edit', 3, array( 'Id' => $tblPerson->getId() ) );
            } else {
                $View .= new MessageDanger( 'Änderungen konnten nicht gespeichert werden' );
            };
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
     * @param AbstractForm $View
     *
     * @param array        $PersonName
     * @param array        $BirthDetail
     * @param array        $PersonInformation
     * @param array        $Button
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
                $PersonName['Title'], $PersonName['First'], $PersonName['Middle'], $PersonName['Last'],
                $BirthDetail['Date'],
                $BirthDetail['Place'], $PersonInformation['Nationality'], $tblPersonSalutation, $tblPersonGender,
                $tblPersonType
            );
            if ($Button['Submit'] == 'Anlegen') {
                return new MessageSuccess( 'Die Person wurde erfolgreich angelegt' )
                .new Redirect( '/Sphere/Management/Person/Create', 2 );
            } else {
                return new MessageSuccess( 'Der Person wurde erfolgreich angelegt' )
                .new Redirect( '/Sphere/Management/Person/Edit', 2, array( 'Id' => $Entity->getId() ) );
            }
        } else {

        }
        return $View;
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return bool|Person\Entity\TblPerson[]
     */
    public function entityPersonAllByType( TblPersonType $tblPersonType )
    {

        return parent::entityPersonAllByType( $tblPersonType );
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return string
     */
    public function tablePersonAllByType( TblPersonType $tblPersonType )
    {

        return parent::tablePersonAllByType( $tblPersonType );
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

    /**
     * @return int
     */
    public function countPersonAll()
    {

        return parent::countPersonAll();
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return int
     */
    public function countPersonAllByType( TblPersonType $tblPersonType )
    {

        return parent::countPersonAllByType( $tblPersonType );
    }

    /**
     * @param int $tblPerson
     *
     * @return string
     */
    public function tablePersonRelationship( $tblPerson )
    {

        return parent::tablePersonRelationship( $tblPerson );
    }

    /**
     * @return bool|TblPersonRelationshipType[]
     */
    public function entityPersonRelationshipTypeAll()
    {

        return parent::entityPersonRelationshipTypeAll();
    }

    /**
     * @param int $tblPersonA
     * @param int $tblPersonB
     * @param int $tblPersonRelationshipType
     *
     * @return TblPersonRelationshipList
     */
    public function executeAddRelationship(
        $tblPersonA,
        $tblPersonB,
        $tblPersonRelationshipType
    ) {

        $tblPersonA = $this->entityPersonById( $tblPersonA );
        $tblPersonB = $this->entityPersonById( $tblPersonB );
        $tblPersonRelationshipType = $this->entityPersonRelationshipTypeById( $tblPersonRelationshipType );
        return parent::actionAddRelationship( $tblPersonA, $tblPersonB, $tblPersonRelationshipType );
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
     * @param int $Id
     *
     * @return bool|TblPersonRelationshipType
     */
    public function entityPersonRelationshipTypeById( $Id )
    {

        return parent::entityPersonRelationshipTypeById( $Id );
    }

    /**
     * @param int $tblPersonA
     * @param int $tblPersonB
     * @param int $tblPersonRelationshipType
     *
     * @return TblPersonRelationshipList
     */
    public function executeRemoveRelationship(
        $tblPersonA,
        $tblPersonB,
        $tblPersonRelationshipType
    ) {

        $tblPersonA = $this->entityPersonById( $tblPersonA );
        $tblPersonB = $this->entityPersonById( $tblPersonB );
        $tblPersonRelationshipType = $this->entityPersonRelationshipTypeById( $tblPersonRelationshipType );
        return parent::actionRemoveRelationship( $tblPersonA, $tblPersonB, $tblPersonRelationshipType );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblPersonRelationshipList
     */
    public function entityPersonRelationshipById( $Id )
    {

        return parent::entityPersonRelationshipById( $Id );
    }


    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblPersonRelationshipList[]
     */
    public function entityPersonRelationshipAllByPerson( TblPerson $tblPerson )
    {

        return parent::entityPersonRelationshipAllByPerson( $tblPerson );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|Effect
     */
    public function executeDestroyPerson( TblPerson $tblPerson )
    {

        $Effect = Management::observerDestroyPerson()->sendWire( new Data( $tblPerson->getId() ) );
        if (true === $Effect) {
            return $this->actionDestroyPerson( $tblPerson );
        } else {
            return $Effect;
        }
    }

    /**
     * @param TblPersonRelationshipList $tblPersonRelationshipList
     *
     * @return bool|Effect
     */
    public function executeDestroyRelationship( TblPersonRelationshipList $tblPersonRelationshipList )
    {

        $Effect = Management::observerDestroyRelationship()->sendWire( new Data( $tblPersonRelationshipList->getId() ) );
        if (true === $Effect) {
            return $this->actionRemoveRelationship(
                $tblPersonRelationshipList->getTblPersonA(),
                $tblPersonRelationshipList->getTblPersonB(),
                $tblPersonRelationshipList->getTblPersonRelationshipType()
            );
        } else {
            return $Effect;
        }
    }

    /**
     * @return Table
     */
    public function getTablePerson()
    {

        return parent::getTablePerson();
    }
}
