<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
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
use Symfony\Component\Console\Helper\Table;

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
                    'Anzahl'   => Management::servicePerson()->countPersonAll()
                ),
                array(
                    'Personen' => new GroupIcon().'&nbsp;&nbsp;Interessenten',
                    'Anzahl'   => Management::servicePerson()->countPersonAllByType(
                        Management::servicePerson()->entityPersonTypeByName( 'Interessent' )
                    )
                ),
                array(
                    'Personen' => new GroupIcon().'&nbsp;&nbsp;Schüler',
                    'Anzahl'   => Management::servicePerson()->countPersonAllByType(
                        Management::servicePerson()->entityPersonTypeByName( 'Schüler' )
                    )
                ),
                array(
                    'Personen' => new GroupIcon().'&nbsp;&nbsp;Sorgeberechtigte',
                    'Anzahl'   => Management::servicePerson()->countPersonAllByType(
                        Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )
                    )
                )
            ), null, array(), false )
        );

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
        $PersonNationality = Management::servicePerson()->listPersonNationality();
        $PersonBirthPlace = Management::servicePerson()->listPersonBirthplace();

        if (!empty( $PersonNationality )) {
            array_walk( $PersonNationality, function ( &$P ) {

                $P = $P['Nationality'];
            } );
        } else {
            $PersonNationality = array();
        }
        $PersonNationality = array_unique( $PersonNationality );

        if (!empty( $PersonBirthPlace )) {
            array_walk( $PersonBirthPlace, function ( &$P ) {

                $P = $P['Birthplace'];
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
        $View->setContent(
            new TableData( '/Sphere/Management/REST/PersonListInterest', null,
                array(
                    'Id'         => '#',
                    'FirstName'  => 'Vorname',
                    'MiddleName' => 'Zweitname',
                    'LastName'   => 'Nachname',
                    'Option'     => 'Option'
                )
            )
        );

//        $tblPersonList = Management::servicePerson()->entityPersonAllByType(
//            Management::servicePerson()->entityPersonTypeByName( 'Interessent' )
//        );
//        if (empty( $tblPersonList )) {
//            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
//        } else {
//            array_walk( $tblPersonList, function ( TblPerson &$P ) {
//
//                $P->Option = new ButtonLinkPrimary( 'Bearbeiten', '/Sphere/Management/Person/Edit', null,
//                    array( 'Id' => $P->getId() )
//                );
//            } );
//            $View->setContent( new TableData( $tblPersonList ) );
//        }
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
        $View->setContent(
            new TableData( '/Sphere/Management/REST/PersonListStudent', null,
                array( 'Id' => '#', 'FirstName' => 'Vorname' ) )
        );
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
        $View->setContent(
            new TableData( '/Sphere/Management/REST/PersonListGuardian', null,
                array( 'Id' => '#', 'FirstName' => 'Vorname' ) )
        );
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
