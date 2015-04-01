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
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
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
        $Form->appendFormButton( new SubmitPrimary( 'Anlegen' ) );
        $Form->appendFormButton( new SubmitPrimary( 'Anlegen & Bearbeiten' ) );

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            $Form, $PersonName, $PersonInformation, $BirthDetail, $Button )
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

        return new Form(
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
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'BirthDetail[Gender]', 'Geschlecht',
                            array( 'Name' => $tblPersonGenderAll ), new ChildIcon()
                        ), 4 ),
                    new FormColumn(
                        new DatePicker( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                        , 4 ),
                    new FormColumn(
                        new AutoCompleter( 'BirthDetail[Place]', 'Geburtsort', 'Geburtsort',
                            $PersonBirthPlace, new MapMarkerIcon()
                        ), 4 ),
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new AutoCompleter( 'PersonInformation[Nationality]', 'Staatsangehörigkeit',
                            'Staatsangehörigkeit', $PersonNationality, new PersonIcon()
                        ), 4 ),
                    new FormColumn(
                        new SelectBox( 'PersonInformation[Type]', 'Art der Person',
                            array( 'Name' => $tblPersonTypeAll ), new GroupIcon()
                        ), 4 )
                ) )
            ), new FormTitle( 'Grunddaten' ) )
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
            new TableData( '/Sphere/Management/Table/PersonInterest', null,
                array(
                    'Id'         => '#',
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

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $View->setContent(
            new TableData( '/Sphere/Management/Table/PersonStudent', null,
                array(
                    'Id'         => '#',
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
    public static function stageListGuardian()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Sorgeberechtigte' );
        $View->setContent(
            new TableData( '/Sphere/Management/Table/PersonGuardian', null,
                array(
                    'Id'         => '#',
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

                $View->setMessage( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() );
                $_POST['PersonName']['Salutation'] = $tblPerson->getTblPersonSalutation()->getId();
                $_POST['PersonName']['Title'] = $tblPerson->getTitle();
                $_POST['PersonName']['First'] = $tblPerson->getFirstName();
                $_POST['PersonName']['Middle'] = $tblPerson->getMiddleName();
                $_POST['PersonName']['Last'] = $tblPerson->getLastName();
                $_POST['BirthDetail']['Gender'] = $tblPerson->getTblPersonGender()->getId();
                $_POST['BirthDetail']['Date'] = $tblPerson->getBirthday();
                $_POST['BirthDetail']['Place'] = $tblPerson->getBirthplace();
                $_POST['PersonInformation']['Nationality'] = $tblPerson->getNationality();
                $_POST['PersonInformation']['Type'] = $tblPerson->getTblPersonType()->getId();
                $FormPersonBasic = self::formPersonBasic();
                $FormPersonBasic->appendFormButton( new SubmitPrimary( 'Änderungen speichern' ) );

                $FormPersonRelationship = self::formPersonRelationship( $tblPerson );

                $View->setContent(
                    Management::servicePerson()->executeChangePerson(
                        $FormPersonBasic, $tblPerson, $PersonName, $PersonInformation, $BirthDetail
                    )
                    .$FormPersonRelationship
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
    private static function formPersonRelationship( TblPerson $tblPerson )
    {

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn(
                        new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship', new PencilIcon(),
                            array(
                                'tblPerson' => $tblPerson->getId()
                            ) )
                        , 4 )
                ) ),
            ), new LayoutTitle( 'Beziehungen' ) )
        );
    }
}
