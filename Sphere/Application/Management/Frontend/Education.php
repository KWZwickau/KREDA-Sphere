<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EnableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\StatisticIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Link\Success;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Client\Frontend\Text\Type\Danger as DangerText;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Client\Frontend\Text\Type\Primary as PrimaryText;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Education extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageEducation()
    {

        $View = new Stage();
        $View->setTitle( 'Klassen und Fächer' );

        return $View;
    }

    /**
     * @param array $Subject
     *
     * @return Stage
     */
    public static function stageSubject( $Subject )
    {

        $View = new Stage();
        $View->setTitle( 'Fächer' );
        $View->addButton( new Primary( 'Fach-Kategorien', '/Sphere/Management/Education/Subject/Category' ) );

        $tblSubject = Management::serviceEducation()->entitySubjectAll();
        if (!empty( $tblSubject )) {
            array_walk( $tblSubject, function ( TblSubject &$tblSubject ) {

                if ($tblSubject->getActiveState()) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $tblSubject->Option = ( new Danger( 'Deaktivieren',
                        '/Sphere/Management/Education/Subject/Disable', new DisableIcon(), array(
                            'Id' => $tblSubject->getId()
                        )
                    ) )->__toString();
                } else {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $tblSubject->Option = ( new Success( 'Aktivieren',
                        '/Sphere/Management/Education/Subject/Enable', new EnableIcon(), array(
                            'Id' => $tblSubject->getId()
                        )
                    ) )->__toString();
                }
            } );
        }

        $View->setContent( new Layout(
            new LayoutGroup( array(
                new LayoutRow(
                    new LayoutColumn( array(
                        new LayoutTitle( 'Fächer', 'Hinzufügen/Deaktivieren' ),
                        new TableData( $tblSubject, null, array(
                            'Acronym' => 'Kürzel',
                            'Name'    => 'Name',
                            'Option'  => 'Option'
                        ) ),
                        Management::serviceEducation()->executeCreateSubject(
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new TextField( 'Subject[Acronym]', 'Kürzel', 'Kürzel' )
                                            , 3 ),
                                        new FormColumn(
                                            new TextField( 'Subject[Name]', 'Name', 'Name' )
                                            , 9 )
                                    ) ),

                                ) ), new SubmitPrimary( 'Hinzufügen' )
                            ), $Subject )
                    ) )
                )
            ) )
        ) );
        return $View;
    }

    /**
     * @param array $Level
     * @param array $Group
     *
     * @return Stage
     */
    public static function stageGroup( $Level, $Group )
    {

        $View = new Stage();
        $View->setTitle( 'Klassen' );

        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Klassenstufen', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityLevelAll(), null, array(
                                'Name'        => 'Klassenstufe',
                                'Description' => 'Beschreibung',
                            ) ),
                            Management::serviceEducation()->executeCreateLevel(
                                new Form(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Level[Name]', 'Name', 'Name' )
                                                , 3 ),
                                            new FormColumn(
                                                new TextField( 'Level[Description]', 'Beschreibung', 'Beschreibung' )
                                                , 9 )
                                        ) ),
                                        new FormRow( array(
                                            new FormColumn(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Level )
                        ), 6 ),
                        new LayoutColumn( array(
                            new LayoutTitle( 'Klassengruppen', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityGroupAll(), null, array(
                                'Name'        => 'Klassengruppe',
                                'Description' => 'Beschreibung',
                            ) ),
                            Management::serviceEducation()->executeCreateGroup(
                                new Form(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Group[Name]', 'Name', 'Name' )
                                                , 3 ),
                                            new FormColumn(
                                                new TextField( 'Group[Description]', 'Beschreibung', 'Beschreibung' )
                                                , 9 )
                                        ) ),
                                        new FormRow( array(
                                            new FormColumn(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Group )
                        ), 6 )
                    ) ),

                ) )
            )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSubjectCategory()
    {

        $View = new Stage();
        $View->setTitle( 'Fächer' );
        $View->setDescription( 'Kategorien' );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSubjectGroup()
    {

        $View = new Stage();
        $View->setTitle( 'Unterrichtsfächer' );

        $tblSubjectGroup = Management::serviceEducation()->entitySubjectGroupAll();
        if (!empty( $tblSubjectGroup )) {
            array_walk( $tblSubjectGroup, function ( TblSubjectGroup &$tblSubjectGroup ) {

                $tblSubjectGroup->displayTerm =
                    new DangerText( $tblSubjectGroup->getTblTerm()->getName() )
                    .new Muted(
                        $tblSubjectGroup->getTblTerm()->getFirstDateFrom()
                        .' - '.$tblSubjectGroup->getTblTerm()->getFirstDateTo()
                    );

                $tblSubjectGroup->displayLevel =
                    new PrimaryText( $tblSubjectGroup->getTblLevel()->getName() )
                    .new Muted( $tblSubjectGroup->getTblLevel()->getDescription() );

                $tblSubjectGroup->displayGroup =
                    new PrimaryText( $tblSubjectGroup->getTblGroup()->getName() )
                    .new Muted( $tblSubjectGroup->getTblGroup()->getDescription() );

                $tblSubjectGroup->displaySubject =
                    new PrimaryText( $tblSubjectGroup->getTblSubject()->getAcronym() )
                    .new Muted( $tblSubjectGroup->getTblSubject()->getName() );

            } );
        }

        $tblLevel = Management::serviceEducation()->entityLevelAll();
        $tblGroup = Management::serviceEducation()->entityGroupAll();
        $tblSubject = Management::serviceEducation()->entitySubjectAll();
        $tblTerm = Management::serviceEducation()->entityTermAll();

        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Bestehende Unterrichtsfächer' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new LayoutTitle( 'Unterrichtsfach', 'hinzufügen' ),
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Term]', 'Zeitraum', array(
                                                '{{Name}} {{ServiceManagementCourse.Name}}' => $tblTerm
                                            ), new TimeIcon() )
                                            , 3 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Level]', 'Klassenstufe',
                                                array( '[{{Name}}] {{Description}}' => $tblLevel ),
                                                new StatisticIcon() )
                                            , 3 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Group]', 'Klassengruppe',
                                                array( '[{{Name}}] {{Description}}' => $tblGroup ), new GroupIcon() )
                                            , 3 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Subject]', 'Fach',
                                                array( '[{{Acronym}}] {{Name}}' => $tblSubject ), new EducationIcon() )
                                            , 3 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new SubmitPrimary( 'Hinzufügen' )
                                        )
                                    ) )
                                ) )
                            ),
                            new LayoutTitle( 'Unterrichtsfächer', 'aus Vorlage erstellen' ),
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn( array(
                                            new Info( 'Kopieren von' ),
                                            new SelectBox( 'SubjectGroup[From][Term]', 'Zeitraum', array(
                                                '{{Name}} {{ServiceManagementCourse.Name}}' => $tblTerm
                                            ), new TimeIcon() ),
                                            new SelectBox( 'SubjectGroup[From][Level]', 'Klassenstufe', array(
                                                '[{{Name}}] {{Description}}' => Management::serviceEducation()->entityLevelAll()
                                            ), new StatisticIcon() ),
                                            new SelectBox( 'SubjectGroup[From][Group]', 'Klassengruppe', array(
                                                '[{{Name}}] {{Description}}' => Management::serviceEducation()->entityGroupAll()
                                            ), new GroupIcon() ),
                                        ), 6 ),
                                        new FormColumn( array(
                                            new Info( 'Hinzufügen zu' ),
                                            new SelectBox( 'SubjectGroup[To][Term]', 'Zeitraum', array(
                                                '{{Name}} {{ServiceManagementCourse.Name}}' => $tblTerm
                                            ), new TimeIcon() ),
                                            new SelectBox( 'SubjectGroup[To][Level]', 'Klassenstufe', array(
                                                '[{{Name}}] {{Description}}' => Management::serviceEducation()->entityLevelAll()
                                            ), new StatisticIcon() ),
                                            new SelectBox( 'SubjectGroup[To][Group]', 'Klassengruppe', array(
                                                '[{{Name}}] {{Description}}' => Management::serviceEducation()->entityGroupAll()
                                            ), new GroupIcon() )
                                        ), 6 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new SubmitPrimary( 'Kopieren' )
                                        )
                                    ) )
                                ) )

                            ),

                        ) )

                    ) )
                ) )
            )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSubjectGroupStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Unterrichtsgruppen' );

        $tblSubjectGroup = Management::serviceEducation()->entitySubjectGroupAll();
        if (!empty( $tblSubjectGroup )) {
            array_walk( $tblSubjectGroup, function ( TblSubjectGroup &$tblSubjectGroup ) {

                $tblSubjectGroup->displayTerm =
                    new DangerText( $tblSubjectGroup->getTblTerm()->getName() )
                    .new Muted(
                        $tblSubjectGroup->getTblTerm()->getFirstDateFrom()
                        .' - '.$tblSubjectGroup->getTblTerm()->getFirstDateTo()
                    );

                $tblSubjectGroup->displayLevel =
                    new PrimaryText( $tblSubjectGroup->getTblLevel()->getName() )
                    .new Muted( $tblSubjectGroup->getTblLevel()->getDescription() );

                $tblSubjectGroup->displayGroup =
                    new PrimaryText( $tblSubjectGroup->getTblGroup()->getName() )
                    .new Muted( $tblSubjectGroup->getTblGroup()->getDescription() );

                $tblSubjectGroup->displaySubject =
                    new PrimaryText( $tblSubjectGroup->getTblSubject()->getAcronym() )
                    .new Muted( $tblSubjectGroup->getTblSubject()->getName() );

            } );
        }

        $tblStudentList = Management::servicePerson()->entityPersonAllByType(
            Management::servicePerson()->entityPersonTypeByName( 'Schüler' )
        );

//TODO:
        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Bestehende Unterrichtsfächer' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new LayoutTitle( 'Schüler', 'hinzufügen/entfernen' ),
                        ) )
                    ) ),
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            ( empty( $tblStudentList )
                                ? new Warning( 'Keine Schüler zugewiesen' )
                                : new TableData( $tblStudentList, null, array(
                                    'LastName' => 'LastName'
                                ) )
                            )
                        ), 6 ),
                        new LayoutColumn( array(
                            ( empty( $tblStudentList )
                                ? new Warning( 'Keine Schüler verfügbar' )
                                : new TableData( $tblStudentList, null, array(
                                    'FirstName' => 'FirstName'
                                ) )
                            )
                        ), 6 )
                    ) )
                ) )
            ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSubjectGroupTeacher()
    {

        $View = new Stage();
        $View->setTitle( 'Lehraufträge' );

        $tblSubjectGroup = Management::serviceEducation()->entitySubjectGroupAll();
        if (!empty( $tblSubjectGroup )) {
            array_walk( $tblSubjectGroup, function ( TblSubjectGroup &$tblSubjectGroup ) {

                $tblSubjectGroup->displayTerm =
                    new DangerText( $tblSubjectGroup->getTblTerm()->getName() )
                    .new Muted(
                        $tblSubjectGroup->getTblTerm()->getFirstDateFrom()
                        .' - '.$tblSubjectGroup->getTblTerm()->getFirstDateTo()
                    );

                $tblSubjectGroup->displayLevel =
                    new PrimaryText( $tblSubjectGroup->getTblLevel()->getName() )
                    .new Muted( $tblSubjectGroup->getTblLevel()->getDescription() );

                $tblSubjectGroup->displayGroup =
                    new PrimaryText( $tblSubjectGroup->getTblGroup()->getName() )
                    .new Muted( $tblSubjectGroup->getTblGroup()->getDescription() );

                $tblSubjectGroup->displaySubject =
                    new PrimaryText( $tblSubjectGroup->getTblSubject()->getAcronym() )
                    .new Muted( $tblSubjectGroup->getTblSubject()->getName() );

            } );
        }

        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Bestehende Unterrichtsfächer' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new LayoutTitle( 'Lehrer', 'hinzufügen/entfernen' ),
                        ) )
                    ) )
                ) )
            ) );

        return $View;
    }
}
