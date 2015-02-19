<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Student\PersonDetail as StudentPersonDetail;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Summary\Summary;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WheelChairIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class PersonalData
 *
 * @package KREDA\Sphere\Application\Management\PersonalData
 */
class PersonalData extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stagePerson()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die Anzahl an Personen in den jeweiligen Personengruppen' );
        $View->setContent( new Summary( array(
            new GroupIcon().'&nbsp;&nbsp;Alle'              => count( Management::servicePerson()->entityPersonAll() ),
            new PersonIcon().'&nbsp;&nbsp;Schüler'          => count( Management::servicePerson()->entityPersonAll() ),
            new PersonIcon().'&nbsp;&nbsp;Sorgeberechtigte' => count( Management::servicePerson()->entityPersonAll() ),
            new PersonIcon().'&nbsp;&nbsp;Lehrer'           => count( Management::servicePerson()->entityPersonAll() ),
            new PersonIcon().'&nbsp;&nbsp;Verwaltung'       => count( Management::servicePerson()->entityPersonAll() ),
            new PersonIcon().'&nbsp;&nbsp;Sonstige'         => count( Management::servicePerson()->entityPersonAll() )
        ) ) );
        return $View;
    }

    /**
     * @param int $Id
     *
     * @return Stage
     */
    public static function stagePersonStudentDetail( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler - Detail' );
        $Data = Management::servicePerson()->entityPersonById( $Id );
        if (empty( $Data )) {
            $Error = self::stagePersonStudent();
            $Error->setMessage( new MessageDanger( 'Keine Daten verfügbar', new WheelChairIcon() ) );
            return $Error;
        } else {
            $View->setContent( new StudentPersonDetail( $Data ) );
        }

        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $PersonList = Management::servicePerson()->entityPersonAll();

        array_walk( $PersonList, function ( TblPerson &$V, $I, $B ) {

            $_POST['Id'] = $V->getId();
            $V->Option = new FormDefault(
                new GridFormGroup(
                    new GridFormRow( new GridFormCol( array(
                        new InputHidden( 'Id' ),
                        new ButtonSubmitPrimary( 'Öffnen' )
                    ) ) )
                ),
                null,
                $B.'/Sphere/Management/Person/Student/Detail'
            );

            $V->FullName = $V->getFirstName().' '.$V->getMiddleName().' '.$V->getLastName();
        }, self::getUrlBase() );
        $View->setContent( new TableData( $PersonList, null, array(
            //'Id' => 'Id',
            'Salutation' => 'Anrede',
            //'FirstName' => 'FirstName',
            'FullName'   => 'Name',
            //'MiddleName' => 'MiddleName',
            //'LastName' => 'LastName',
            'Gender'     => 'Gender',
            'Birthday'   => 'Birthday',
            'Option'     => 'Option'
        ) ) );
        $View->addButton( '/Sphere/Management/Person/Student/Create', 'Schüler hinzufügen' );
        $View->addButton( '/Sphere/Management/Person/Student/Import', 'Schüler importieren' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonStudentCreate()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler hinzufügen' );
        $View->setMessage( '' );
        $View->setContent(
            new FormDefault(
                new GridFormGroup( array(
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputText( 'PersonTitle', 'Anrede', 'Anrede', new ConversationIcon() )
                        , 4 )
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputText( 'PersonFirstName', 'Vorname', 'Vorname', new NameplateIcon() )
                        , 4 ),
                        new GridFormCol(
                            new InputText( 'PersonMiddleName', 'Zweitname', 'Zweitname', new NameplateIcon() )
                        , 4 ),
                        new GridFormCol(
                            new InputText( 'PersonLastName', 'Nachname', 'Nachname', new NameplateIcon() )
                        , 4 )
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputSelect( 'PersonGender', 'Geschlecht', array(
                                'Männlich',
                                'Weiblich'
                            ), new PersonIcon()
                        ), 4 ),
                        new GridFormCol(
                            new InputDate( 'PersonBirthday', 'Geburtstag', 'Geburtstag', new PersonIcon() )
                        , 4 ),
                        new GridFormCol(
                            new InputCompleter( 'PersonCity', 'Geburtsort', 'Geburtsort', array(
                                'Alabama',
                                'Alaska',
                                'Arizona'
                            ), new PersonIcon()
                        ), 4 ),
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputCompleter( 'PersonNationality', 'Staatsangehörigkeit', 'Staatsangehörigkeit', array(
                                'Deutsch'
                            ), new PersonIcon()
                        ), 6 ),
                        new GridFormCol(
                            new InputCompleter( 'PersonState', 'Bundesland', 'Bundesland', array(
                                'Sachsen'
                            ), new PersonIcon()
                        ), 6 )
                    ) )
                ), new GridFormTitle( 'Grunddaten' ) ), array(
                    new ButtonSubmitPrimary( 'Anlegen' )
                )
            )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonTeacher()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Lehrer' );
        $PersonList = Management::servicePerson()->entityPersonAll();
        array_walk( $PersonList, function ( TblPerson &$V, $I, $B ) {

            $_POST['Id'] = $V->getId();
            $V->Option = new FormDefault(
                new GridFormGroup(
                    new GridFormRow( new GridFormCol( array(
                        new InputHidden( 'Id' ),
                        new ButtonSubmitPrimary( 'Öffnen' )
                    ) ) )
                ),
                null,
                $B.'/Sphere/Management/Person/Teacher/Detail'
            );

        }, self::getUrlBase() );
        $View->setContent( new TableData( $PersonList ) );
        $View->addButton( '/Sphere/Management/Person/Teacher/Create', 'Lehrer hinzufügen' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonGuardian()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Sorgeberechtigte' );
        $PersonList = Management::servicePerson()->entityPersonAll();
        array_walk( $PersonList, function ( TblPerson &$V, $I, $B ) {

            $_POST['Id'] = $V->getId();
            $V->Option = new FormDefault(
                new GridFormGroup(
                    new GridFormRow( new GridFormCol( array(
                        new InputHidden( 'Id' ),
                        new ButtonSubmitPrimary( 'Öffnen' )
                    ) ) )
                ),
                null,
                $B.'/Sphere/Management/Person/Guardian/Detail'
            );

        }, self::getUrlBase() );
        $View->setContent( new TableData( $PersonList ) );
        $View->addButton( '/Sphere/Management/Person/Guardian/Create', 'Sorgeberechtigte hinzufügen' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonStaff()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Verwaltung' );
        $PersonList = Management::servicePerson()->entityPersonAll();
        array_walk( $PersonList, function ( TblPerson &$V, $I, $B ) {

            $_POST['Id'] = $V->getId();
            $V->Option = new FormDefault(
                new GridFormGroup(
                    new GridFormRow( new GridFormCol( array(
                        new InputHidden( 'Id' ),
                        new ButtonSubmitPrimary( 'Öffnen' )
                    ) ) )
                ),
                null,
                $B.'/Sphere/Management/Person/Staff/Detail'
            );

        }, self::getUrlBase() );
        $View->setContent( new TableData( $PersonList ) );
        $View->addButton( '/Sphere/Management/Person/Staff/Create', 'Mitarbeiter hinzufügen' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stagePersonOthers()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Sonstige' );
        $PersonList = Management::servicePerson()->entityPersonAll();
        array_walk( $PersonList, function ( TblPerson &$V, $I, $B ) {

            $_POST['Id'] = $V->getId();
            $V->Option = new FormDefault(
                new GridFormGroup(
                    new GridFormRow( new GridFormCol( array(
                        new InputHidden( 'Id' ),
                        new ButtonSubmitPrimary( 'Öffnen' )
                    ) ) )
                ),
                null,
                $B.'/Sphere/Management/Person/Others/Detail'
            );

        }, self::getUrlBase() );
        $View->setContent( new TableData( $PersonList ) );
        $View->addButton( '/Sphere/Management/Person/Others/Create', 'Person hinzufügen' );
        return $View;
    }

}
