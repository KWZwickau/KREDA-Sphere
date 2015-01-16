<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Guardian\PersonList as GuardianPersonList;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Others\PersonList as OthersPersonList;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Staff\PersonList as StaffPersonList;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Student\PersonDetail as StudentPersonDetail;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Student\PersonList as StudentPersonList;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Summary\Summary;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Teacher\PersonList as TeacherPersonList;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputDate;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputText;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;

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
     * @return Stage
     */
    public static function stagePersonStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $View->setMessage( '' );
        $View->setContent( new StudentPersonList( Management::servicePerson()->entityPersonAll() ) );
        $View->addButton( '/Sphere/Management/Person/Student/Create', 'Schüler hinzufügen' );
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
        $View->setMessage( '' );
        $View->setContent( new StudentPersonDetail( Management::servicePerson()->entityPersonById( $Id ) ) );
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
                ), 'Grunddaten' ), array(
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
        $View->setMessage( '' );
        $View->setContent( new TeacherPersonList( Management::servicePerson()->entityPersonAll() ) );
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
        $View->setMessage( '' );
        $View->setContent( new GuardianPersonList( Management::servicePerson()->entityPersonAll() ) );
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
        $View->setMessage( '' );
        $View->setContent( new StaffPersonList( Management::servicePerson()->entityPersonAll() ) );
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
        $View->setMessage( '' );
        $View->setContent( new OthersPersonList( Management::servicePerson()->entityPersonAll() ) );
        $View->addButton( '/Sphere/Management/Person/Others/Create', 'Person hinzufügen' );
        return $View;
    }

}
