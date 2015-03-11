<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitSuccess;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
        $View->setContent( new TableData( array(
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Alle',
                'Anzahl'   => count( ( $tblPersonList = Management::servicePerson()->entityPersonAll()
                ) ? $tblPersonList : array() )
            ),
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Interessenten',
                'Anzahl'   => count( ( $tblPersonList = Management::servicePerson()->entityPersonAllByType(
                    Management::servicePerson()->entityPersonTypeByName( 'Interessent' )
                ) ) ? $tblPersonList : array() )
            ),
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Schüler',
                'Anzahl'   => count( ( $tblPersonList = Management::servicePerson()->entityPersonAllByType(
                    Management::servicePerson()->entityPersonTypeByName( 'Schüler' )
                ) ) ? $tblPersonList : array() )
            ),
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Sorgeberechtigte',
                'Anzahl'   => count( ( $tblPersonList = Management::servicePerson()->entityPersonAllByType(
                    Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )
                ) ) ? $tblPersonList : array() )
            )
        ), null, array(), false ) );

        return $View;
    }

    /**
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     * @param null|array $Button
     *
     * @return Stage
     */
    public static function stageCreate( $PersonName, $PersonInformation, $BirthDetail, $Button )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Hinzufügen' );

        $Form = self::formPersonBasic();
        $Form->appendFormButton( new ButtonSubmitSuccess( 'Anlegen' ) );
        $Form->appendFormButton( new ButtonSubmitPrimary( 'Anlegen & Weiter' ) );

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            $Form, $PersonName, $PersonInformation, $BirthDetail, $Button )
        );
        return $View;
    }

    /**
     * @return FormDefault
     */
    private static function formPersonBasic()
    {

        $tblPersonSalutationAll = Management::servicePerson()->entityPersonSalutationAll();
        $tblPersonGenderAll = Management::servicePerson()->entityPersonGenderAll();
        $tblPersonTypeAll = Management::servicePerson()->entityPersonTypeAll();
        $PersonNationality = Management::servicePerson()->entityPersonAll();
        $PersonBirthPlace = Management::servicePerson()->entityPersonAll();
        if (!empty( $PersonNationality )) {
            array_walk( $PersonNationality, function ( TblPerson &$P ) {

                $P = $P->getNationality();
            } );
        } else {
            $PersonNationality = array();
        }
        $PersonNationality = array_unique( $PersonNationality );

        if (!empty( $PersonBirthPlace )) {
            array_walk( $PersonBirthPlace, function ( TblPerson &$P ) {

                $P = $P->getBirthplace();
            } );
        } else {
            $PersonBirthPlace = array();
        }
        $PersonBirthPlace = array_unique( $PersonBirthPlace );

        return new FormDefault(
            new GridFormGroup( array(
                new GridFormRow( array(
                    new GridFormCol(
                        new InputSelect( 'PersonName[Salutation]', 'Anrede',
                            array( 'Name' => $tblPersonSalutationAll ), new ConversationIcon()
                        ), 4 )
                ) ),
                new GridFormRow( array(
                    new GridFormCol(
                        new InputText( 'PersonName[First]', 'Vorname', 'Vorname', new NameplateIcon() )
                        , 4 ),
                    new GridFormCol(
                        new InputText( 'PersonName[Middle]', 'Zweitname', 'Zweitname', new NameplateIcon() )
                        , 4 ),
                    new GridFormCol(
                        new InputText( 'PersonName[Last]', 'Nachname', 'Nachname', new NameplateIcon() )
                        , 4 )
                ) ),
                new GridFormRow( array(
                    new GridFormCol(
                        new InputSelect( 'BirthDetail[Gender]', 'Geschlecht',
                            array( 'Name' => $tblPersonGenderAll ), new ChildIcon()
                        ), 4 ),
                    new GridFormCol(
                        new InputDate( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                        , 4 ),
                    new GridFormCol(
                        new InputCompleter( 'BirthDetail[Place]', 'Geburtsort', 'Geburtsort',
                            $PersonBirthPlace, new MapMarkerIcon()
                        ), 4 ),
                ) ),
                new GridFormRow( array(
                    new GridFormCol(
                        new InputCompleter( 'PersonInformation[Nationality]', 'Staatsangehörigkeit',
                            'Staatsangehörigkeit', $PersonNationality, new PersonIcon()
                        ), 4 ),
                    new GridFormCol(
                        new InputSelect( 'PersonInformation[Type]', 'Art der Person',
                            array( 'Name' => $tblPersonTypeAll ), new GroupIcon()
                        ), 4 )
                ) )
            ), new GridFormTitle( 'Grunddaten' ) )
        );
    }

    /**
     * @return Stage
     */
    public static function stageListInterest()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Interessenten' );
        $tblPersonList = Management::servicePerson()->entityPersonAllByType(
            Management::servicePerson()->entityPersonTypeByName( 'Interessent' )
        );
        if (empty( $tblPersonList )) {
            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
        } else {
            array_walk( $tblPersonList, function ( TblPerson &$P ) {

                $P->Option = new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
                    array( 'Id' => $P->getId() )
                );
            } );
            $View->setContent( new TableData( $tblPersonList ) );
        }
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageListStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $tblPersonList = Management::servicePerson()->entityPersonAllByType(
            Management::servicePerson()->entityPersonTypeByName( 'Schüler' )
        );
        if (empty( $tblPersonList )) {
            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
        } else {
            array_walk( $tblPersonList, function ( TblPerson &$P ) {

                $P->Option = new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
                    array( 'Id' => $P->getId() )
                );
            } );
            $View->setContent( new TableData( $tblPersonList ) );
        }
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageListGuardian()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Sorgeberechtigte' );
        $tblPersonList = Management::servicePerson()->entityPersonAllByType(
            Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )
        );
        if (empty( $tblPersonList )) {
            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
        } else {
            array_walk( $tblPersonList, function ( TblPerson &$P ) {

                $P->Option = new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
                    array( 'Id' => $P->getId() )
                );
            } );
            $View->setContent( new TableData( $tblPersonList ) );
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
            $View->setContent( new MessageWarning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new MessageWarning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {

                $View->setMessage( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() );
                $_POST['PersonName']['Salutation'] = $tblPerson->getTblPersonSalutation()->getId();
                $_POST['PersonName']['First'] = $tblPerson->getFirstName();
                $_POST['PersonName']['Middle'] = $tblPerson->getMiddleName();
                $_POST['PersonName']['Last'] = $tblPerson->getLastName();
                $_POST['BirthDetail']['Gender'] = $tblPerson->getTblPersonGender()->getId();
                $_POST['BirthDetail']['Date'] = $tblPerson->getBirthday();
                $_POST['BirthDetail']['Place'] = $tblPerson->getBirthplace();
                $_POST['PersonInformation']['Nationality'] = $tblPerson->getNationality();
                $_POST['PersonInformation']['Type'] = $tblPerson->getTblPersonType()->getId();
                $FormPersonBasic = self::formPersonBasic();
                $FormPersonBasic->appendFormButton( new ButtonSubmitSuccess( 'Änderungen speichern' ) );

                $FormPersonGuardian = self::formPersonRelationshipGuardian();
                $FormPersonGuardian->appendFormButton( new ButtonSubmitPrimary( 'Hinzufügen' ) );

                $View->setContent(
                    Management::servicePerson()->executeChangePerson(
                        $FormPersonBasic, $tblPerson, $PersonName, $PersonInformation, $BirthDetail
                    )
                    .$FormPersonGuardian
                );
            }
        }
        return $View;
    }

    /**
     * @return FormDefault
     */
    private static function formPersonRelationshipGuardian()
    {

        return new FormDefault(
            new GridFormGroup( array(
                new GridFormRow( array(
                    new GridFormCol(
                        array()
                        , 4 )
                ) ),
            ), new GridFormTitle( 'Sorgeberechtigte' ) )
        );
    }
}
