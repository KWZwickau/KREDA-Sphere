<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TempleChurchIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormAspect;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\NumberField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextArea;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Person extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die Anzahl an Personen in den jeweiligen Personengruppen' );

        $tblPersonTypeAll = Management::servicePerson()->entityPersonTypeAll();
        $DataList = array(
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Alle',
                'Anzahl'   => Management::servicePerson()->countPersonAll()
            )
        );
        /** @var TblPersonType $tblPersonType */
        foreach ((array)$tblPersonTypeAll as $tblPersonType) {
            array_push( $DataList,
                array(
                    'Personen' => new GroupIcon().'&nbsp;&nbsp;'.$tblPersonType->getName(),
                    'Anzahl'   => Management::servicePerson()->countPersonAllByType( $tblPersonType )
                )
            );
        }
        $View->setContent( new TableData( $DataList, null, array(), false ) );

        return $View;
    }

    /**
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @return Stage
     */
    public static function stageCreate( $PersonName, $PersonInformation, $BirthDetail )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Hinzufügen' );

        $Form = self::formPersonBasic();
        $Form->appendFormButton( new SubmitPrimary( 'Anlegen' ) );

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            $Form, $PersonName, $PersonInformation, $BirthDetail )
        );
        return $View;
    }

    /**
     * @return Form
     */
    private static function formPersonBasic()
    {

        $tblPersonSalutationAll = Management::servicePerson()->entityPersonSalutationAll();
        $tblPersonGenderAll = Management::servicePerson()->entityPersonGenderAll();
        $tblPersonTypeAll = Management::servicePerson()->entityPersonTypeAll();
        $PersonNationality = Management::servicePerson()->listPersonNationality();
        $PersonDenomination = Management::servicePerson()->listPersonDenomination();
        $PersonBirthPlace = Management::servicePerson()->listPersonBirthplace();
        $AddressCity = Management::serviceAddress()->entityAddressCityAll();

        if (!empty( $PersonNationality )) {
            array_walk( $PersonNationality, function ( &$P ) {

                $P = $P['Nationality'];
            } );
        } else {
            $PersonNationality = array();
        }
        $PersonNationality = array_unique( $PersonNationality );

        if (!empty( $PersonDenomination )) {
            array_walk( $PersonDenomination, function ( &$P ) {

                $P = $P['Denomination'];
            } );
        } else {
            $PersonDenomination = array();
        }
        $PersonDenomination = array_unique( $PersonDenomination );

        if (!empty( $PersonBirthPlace )) {
            array_walk( $PersonBirthPlace, function ( &$P ) {

                $P = $P['Birthplace'];
            } );
        } else {
            $PersonBirthPlace = array();
        }
        $PersonBirthPlace = array_unique( $PersonBirthPlace );

        if (!empty( $AddressCity )) {
            array_walk( $AddressCity, function ( TblAddressCity &$tblAddressCity ) {

                $tblAddressCity = $tblAddressCity->getName();
            } );
        } else {
            $AddressCity = array();
        }
        $AddressCity = array_unique( $AddressCity );

        $PersonBirthPlace = array_unique( array_merge( $PersonBirthPlace, $AddressCity ) );

        return new Form( array(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'PersonInformation[Type]', 'Art der Person',
                            array( 'Name' => $tblPersonTypeAll ), new GroupIcon()
                        ), 4 )
                ) ),
            ), new FormTitle( 'Grunddaten' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'PersonName[Salutation]', 'Anrede',
                            array( 'Name' => $tblPersonSalutationAll ), new ConversationIcon()
                        ), 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Title]', 'Titel', 'Titel', new ConversationIcon()
                        ), 4 )
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'PersonName[First]', 'Vorname', 'Vorname', new NameplateIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Middle]', 'Zweitname', 'Zweitname', new NameplateIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Last]', 'Nachname', 'Nachname', new NameplateIcon() )
                        , 4 )
                ) ),
            ), new FormAspect( 'Name' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'BirthDetail[Gender]', 'Geschlecht',
                            array( 'Name' => $tblPersonGenderAll ), new ChildIcon()
                        ), 2 ),
                    new FormColumn(
                        new DatePicker( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                        , 2 ),
                    new FormColumn(
                        new AutoCompleter( 'BirthDetail[Place]', 'Geburtsort', 'Geburtsort',
                            $PersonBirthPlace, new MapMarkerIcon()
                        ), 4 ),
                    new FormColumn(
                        new AutoCompleter( 'PersonInformation[Nationality]', 'Staatsangehörigkeit',
                            'Staatsangehörigkeit', $PersonNationality, new PersonIcon()
                        ), 4 ),
                ) ),
            ), new FormAspect( 'Geburtsdaten' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new AutoCompleter( 'PersonInformation[Denomination]', 'Konfession',
                            'Konfession', $PersonDenomination, new PersonIcon()
                        ), 4 ),
                    new FormColumn(
                        new TextArea( 'PersonInformation[Remark]', 'Bemerkungen',
                            'Bemerkungen', new PencilIcon()
                        ), 8 ),
                ) ),
            ), new FormAspect( 'Informationen' ) )
        ) );
    }

    /**
     * @return Stage
     */
    public static function stageListInterest()
    {

        return self::listPersonTable( 'Interessenten', '/Sphere/Management/Table/PersonInterest' );
    }

    /**
     * @param $Name
     * @param $RestUri
     *
     * @return Stage
     */
    private static function listPersonTable( $Name, $RestUri )
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( $Name );
        $View->setContent(
            new TableData( $RestUri, null,
                array(
                    'FirstName'  => 'Vorname',
                    'MiddleName' => 'Zweitname',
                    'LastName'   => 'Nachname',
                    'Birthday'   => 'Geburtstag',
                    'Birthplace' => 'Geburtsort',
                    'Option'     => 'Option'
                )
            )
        );
        return $View;

    }

    /**
     * @return Stage
     */
    public static function stageListStudent()
    {

        return self::listPersonTable( 'Schüler', '/Sphere/Management/Table/PersonStudent' );
    }

    /**
     * @return Stage
     */
    public static function stageListGuardian()
    {

        return self::listPersonTable( 'Sorgeberechtigte', '/Sphere/Management/Table/PersonGuardian' );
    }

    /**
     * @return Stage
     */
    public static function stageListTeacher()
    {

        return self::listPersonTable( 'Lehrer', '/Sphere/Management/Table/PersonTeacher' );
    }

    /**
     * @param integer $Id
     *
     * @return Stage
     */
    public static function stageDestroy( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Löschen' );
        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {
                if (true !== ( $Effect = Management::servicePerson()->executeDestroyPerson( $tblPerson ) )) {
                    $View->setContent( $Effect );
                } else {
                    $View->setContent( self::getRedirect( '/Sphere/Management/Person', 2 ) );
                }
            }
        }
        return $View;
    }

    /**
     * @param integer    $Id
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $PersonName, $PersonInformation, $BirthDetail )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Bearbeiten' );
        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['PersonName']['Salutation'] = $tblPerson->getTblPersonSalutation()->getId();
                $Global->POST['PersonName']['Title'] = $tblPerson->getTitle();
                $Global->POST['PersonName']['First'] = $tblPerson->getFirstName();
                $Global->POST['PersonName']['Middle'] = $tblPerson->getMiddleName();
                $Global->POST['PersonName']['Last'] = $tblPerson->getLastName();
                $Global->POST['BirthDetail']['Gender'] = $tblPerson->getTblPersonGender()->getId();
                $Global->POST['BirthDetail']['Date'] = $tblPerson->getBirthday();
                $Global->POST['BirthDetail']['Place'] = $tblPerson->getBirthplace();
                $Global->POST['PersonInformation']['Nationality'] = $tblPerson->getNationality();
                $Global->POST['PersonInformation']['Type'] = $tblPerson->getTblPersonType()->getId();
                $Global->POST['PersonInformation']['Remark'] = $tblPerson->getRemark();
                $Global->POST['PersonInformation']['Denomination'] = $tblPerson->getDenomination();
                $Global->savePost();

                $FormPerson = self::formPersonBasic();
                $FormPerson->appendFormButton( new SubmitPrimary( 'Änderungen speichern' ) );

                /**
                 * Additional
                 */
                switch ($tblPerson->getTblPersonType()->getId()) {
                    case Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId():
                        $FormStudent = self::formStudent( $tblPerson );
                        $FormRelationship = self::formPersonRelationship( $tblPerson );
                        $FormAddress = self::formPersonAddress( $tblPerson );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )->getId():
                        $FormStudent = '';
                        $FormRelationship = self::formPersonRelationship( $tblPerson );
                        $FormAddress = self::formPersonAddress( $tblPerson );
                        break;
                    default:
                        $FormStudent = '';
                        $FormRelationship = '';
                        $FormAddress = '';
                }

                $View->setContent(
                    new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() )
                    .Management::servicePerson()->executeChangePerson(
                        $FormPerson, $tblPerson, $PersonName, $PersonInformation, $BirthDetail
                    )
                    .$FormStudent
                    .$FormAddress
                    .$FormRelationship
                );
            }
        }
        return $View;
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    private static function formStudent( TblPerson $tblPerson )
    {

        $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );

        if ($tblStudent) {
            $Global = self::extensionSuperGlobal();
            $Global->POST['Identifier'] = $tblStudent->getStudentNumber();
            $Global->POST['Course'] = $tblStudent->getServiceManagementCourse()->getId();
            $Global->POST['Transfer']['From']['Date'] = $tblStudent->getTransferFromDate();
            $Global->POST['Transfer']['To']['Date'] = $tblStudent->getTransferToDate();
            $Global->savePost();
        }

        $tblCourseList = Management::serviceCourse()->entityCourseAll();

        $tblSubjectReligion = Management::serviceEducation()->entitySubjectAllByCategory(
            Management::serviceEducation()->entityCategoryByName( 'Religion' )
        );
        array_unshift( $tblSubjectReligion, new TblSubject( null ) );
        $tblSubjectLanguage = Management::serviceEducation()->entitySubjectAllByCategory(
            Management::serviceEducation()->entityCategoryByName( 'Fremdsprache' )
        );
        array_unshift( $tblSubjectLanguage, new TblSubject( null ) );
        $tblSubjectProfile = Management::serviceEducation()->entitySubjectAllByCategory(
            Management::serviceEducation()->entityCategoryByName( 'Profil' )
        );
        array_unshift( $tblSubjectProfile, new TblSubject( null ) );
        $tblSubjectAddiction = Management::serviceEducation()->entitySubjectAllByCategory(
            Management::serviceEducation()->entityCategoryByName( 'Neigungskurs' )
        );
        array_unshift( $tblSubjectAddiction, new TblSubject( null ) );

        $tblSchoolList = Gatekeeper::serviceConsumer()->entityConsumerAll();
        array_unshift( $tblSchoolList, new TblConsumer( null ) );

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn(
                        new Form(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Identifier', 'Schülernummer', 'Schülernummer',
                                            new BarCodeIcon() )
                                        , 3 ),
                                    new FormColumn(
                                        new SelectBox( 'Course', 'Bildungsgang', array( 'Name' => $tblCourseList ),
                                            new EducationIcon()
                                        )
                                        , 3 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new DatePicker( 'Transfer[From][Date]', 'Aufnahmedatum', 'Aufnahmedatum' )
                                        , 3 ),
                                    new FormColumn(
                                        new SelectBox( 'Transfer[From][School]', 'Abgebende Schule',
                                            array( 'Name' => $tblSchoolList ) )
                                        , 9 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new DatePicker( 'Transfer[To][Date]', 'Abgabedatum', 'Abgabedatum' )
                                        , 3 ),
                                    new FormColumn(
                                        new SelectBox( 'Transfer[To][School]', 'Aufnehmende Schule',
                                            array( 'Name' => $tblSchoolList ) )
                                        , 9 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Subject[Religion][Type]', 'Religionsunterricht',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectReligion ),
                                            new TempleChurchIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Religion][Year]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Subject[Language][Type][]', 'Fremdsprache 1',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectLanguage ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Language][Year][]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                    new FormColumn(
                                        new SelectBox( 'Subject[Language][Type][]', 'Fremdsprache 2',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectLanguage ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Language][Year][]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                    new FormColumn(
                                        new SelectBox( 'Subject[Language][Type][]', 'Fremdsprache 3',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectLanguage ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Language][Year][]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Subject[Profile][Type][]', 'Profil 1',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectProfile ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Profile][Year][]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                    new FormColumn(
                                        new SelectBox( 'Subject[Profile][Type][]', 'Profil 2',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectProfile ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Profile][Year][]', 'Jahre', 'Jahre', new TimeIcon() )
                                        , 2 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Subject[Addiction][Type][]', 'Neigungskurs',
                                            array( '[{{ Acronym }}] {{ Name }}' => $tblSubjectAddiction ),
                                            new ConversationIcon()
                                        )
                                        , 4 ),
                                    new FormColumn(
                                        new NumberField( 'Subject[Addiction][Year][]', 'Jahre', 'Jahre',
                                            new TimeIcon() )
                                        , 2 ),
                                ) ),
                            ) ), new SubmitPrimary( 'Schülerdaten speichern' )
                        )
                    )
                ) ),
            ), new LayoutTitle( 'Schülerdaten' ) )
        );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    private static function formPersonRelationship( TblPerson $tblPerson )
    {

        $tblRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );

        if (!empty( $tblRelationshipList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblRelationshipList,
                function ( TblPersonRelationshipList &$tblPersonRelationshipList, $Index, TblPerson $tblPerson ) {

                    $Person = $tblPersonRelationshipList->getTblPersonA();
                    if ($Person->getId() == $tblPerson->getId()) {
                        $Person = $tblPersonRelationshipList->getTblPersonB();
                    }
                    $tblPersonRelationshipList->Person = $Person->getFullName().' ('.$Person->getTblPersonType()->getName().')';
                    $tblPersonRelationshipList->Relationship = $tblPersonRelationshipList->getTblPersonRelationshipType()->getName();
                    $tblPersonRelationshipList->Option = new Danger( 'Entfernen', '', new RemoveIcon() );
                }, $tblPerson );
        }
        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                            new TableData( $tblRelationshipList, null, array(
                                'Person'       => 'Person',
                                'Relationship' => 'Beziehung',
                                'Option'       => 'Option'
                            ) ),
                            new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship', new PencilIcon(),
                                array(
                                    'tblPerson' => $tblPerson->getId()
                                ) ),
                        )
                    )
                ) ),
            ), new LayoutTitle( 'Beziehungen' ) )
        );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    private static function formPersonAddress( TblPerson $tblPerson )
    {

        $tblAddressState = Management::serviceAddress()->entityAddressStateAll();

        $tblAddressStreet = Management::serviceAddress()->entityAddressAll();
        if (!empty( $tblAddressStreet )) {
            array_walk( $tblAddressStreet, function ( TblAddress &$tblAddress ) {

                $tblAddress = $tblAddress->getStreetName();
            } );
        }
        $tblAddressStreet = array_unique( $tblAddressStreet );

        $tblAddressCity = Management::serviceAddress()->entityAddressAll();
        if (!empty( $tblAddressCity )) {
            array_walk( $tblAddressCity, function ( TblAddress &$tblAddress ) {

                $tblAddress = $tblAddress->getTblAddressCity()->getName();
            } );
        }
        $tblAddressCity = array_unique( $tblAddressCity );

        $tblPersonAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );
        if (!empty( $tblPersonAddressList )) {
            array_walk( $tblPersonAddressList, function ( TblAddress &$tblPersonAddress ) {

                $tblPersonAddress->Code = $tblPersonAddress->getTblAddressCity()->getCode();
                $tblPersonAddress->Name = $tblPersonAddress->getTblAddressCity()->getName();
                $tblPersonAddress->District = $tblPersonAddress->getTblAddressCity()->getDistrict();
                $tblPersonAddress->State = $tblPersonAddress->getTblAddressState()->getName();

            } );
        }

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new TableData( $tblPersonAddressList, null, array(
                            'StreetName'   => 'Strasse',
                            'StreetNumber' => 'Hausnummer',
                            'Code'         => 'Postleitzahl',
                            'Name'         => 'Ort',
                            'District'     => 'Ortsteil',
                            'State'        => 'Bundesland',
                        ) ),
                        new Form(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new AutoCompleter( 'Street[Name]', 'Strasse', 'Strasse', $tblAddressStreet )
                                        , 5 ),
                                    new FormColumn(
                                        new NumberField( 'Street[Number]', 'Hausnummer', 'Hausnummer' )
                                        , 2 ),
//                                    new FormColumn(
//                                        new NumberField( 'PostOffice[Box]', 'Postfach', 'Postfach' )
//                                        , 5 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'City[Code]', 'Postleitzahl', 'Postleitzahl' )
                                        , 2 ),
                                    new FormColumn(
                                        new AutoCompleter( 'City[Name]', 'Ort', 'Ort', $tblAddressCity )
                                        , 5 ),
                                    new FormColumn(
                                        new TextField( 'City[District]', 'Ortsteil', 'Ortsteil' )
                                        , 5 ),
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'State', 'Bundesland', array(
                                            'Name' => $tblAddressState
                                        ) )
                                        , 5 ),
                                ) ),
                            ) ), new SubmitPrimary( 'Adresse hinzufügen' ) )
                    ) )
                ) ),
            ), new LayoutTitle( 'Adressdaten' ) )
        );

    }
}
