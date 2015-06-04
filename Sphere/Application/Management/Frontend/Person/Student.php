<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TempleChurchIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\NumberField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAspect;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutBadge;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Common\AbstractExtension;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Student
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Student extends AbstractFrontend
{

    /**
     * @param TblPerson $tblPerson
     *
     * @return Layout
     */
    public static function layoutStudent( TblPerson $tblPerson )
    {

        $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutPanel( 'Schülernummer',
                            ( $tblStudent
                                ? $tblStudent->getStudentNumber()
                                : new Danger( 'Nicht vergeben', new WarningIcon() )
                            ), LayoutPanel::PANEL_TYPE_DANGER )
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutPanel( 'Bildungsgang',
                            ( $tblStudent
                                ? $tblStudent->getServiceManagementCourse()->getName().
                                new Muted( $tblStudent->getServiceManagementCourse()->getDescription() )
                                : new Danger( 'Nicht angegeben', new WarningIcon() )
                            ), LayoutPanel::PANEL_TYPE_WARNING
                        )
                    ), 9 ),
                ) ),
                new LayoutRow(
                    new LayoutColumn(
                        new LayoutAspect( 'Schülertransfer' )
                    )
                ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutPanel( 'Aufnahme des Schülers', array(
                                '',
                                ( $tblStudent
                                    ? $tblStudent->getTransferFromDate()
                                    : new Danger( 'Nicht angegeben', new WarningIcon() )
                                ),
                                'Name der abgebenden Schule',
                            )
                        ),
                    ), 6 ),
                    new LayoutColumn( array(
                        new LayoutPanel( 'Abgabe des Schülers', array(
                                '',
                                ( $tblStudent && $tblStudent->getTransferToDate()
                                    ? $tblStudent->getTransferToDate()
                                    : 'Nicht angegeben'
                                ),
                                'Name der aufnehmenden Schule',
                            )
                        ),
                    ), 6 ),
                ) ),
                new LayoutRow(
                    new LayoutColumn(
                        new LayoutAspect( 'Unterrichtsfächer' )
                    )
                ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutPanel( 'Religionsunterricht', array( '', 'Ethik'.new LayoutBadge( '2 Jahre' ) )
                        )
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutPanel( 'Fremdsprachen', array(
                            '',
                            '1. Englisch'.new LayoutBadge( '3 Jahre' ),
                            '2. Russisch'.new LayoutBadge( '2 Jahre' )
                        ) )
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutPanel( 'Profil', array(
                            '',
                            '1. Profil'.new LayoutBadge( 'x Jahre' ),
                            '2. Name'.new LayoutBadge( 'x Jahre' ),
                        ) )
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutPanel( 'Neigungskurs', array(
                            '',
                            '1. Name'.new LayoutBadge( 'x Jahre' ),
                            '2. Name'.new LayoutBadge( 'x Jahre' ),
                        ) )
                    ), 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new Primary( 'Bearbeiten', '/Sphere/Management/Person/Student/Edit', new PencilIcon(),
                            array( 'Id' => $tblPerson->getId() )
                        )
                    ) ),
                ) ),
            ), new LayoutTitle( 'Schülerdaten' ) )
        );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    public static function formStudent( TblPerson $tblPerson )
    {

        $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );

        if ($tblStudent) {
            $Global = AbstractExtension::extensionSuperGlobal();
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
}
