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
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
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
                        new LayoutAspect( 'Schülernummer' ),
                        new Success( $tblStudent->getStudentNumber() )
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Bildungsgang' ),
                        new Warning(
                            $tblStudent->getServiceManagementCourse()->getName().
                            new Muted( $tblStudent->getServiceManagementCourse()->getDescription() )
                        )
                    ), 9 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Aufnahmedatum' ),
                        $tblStudent->getTransferFromDate()
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Abgebende Schule' ),
                        '&nbsp;'
                    ), 9 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Abgabedatum' ),
                        $tblStudent->getTransferToDate()
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Aufnehmende Schule' ),
                        '&nbsp;'
                    ), 9 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Religionsunterricht' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Fremdsprache 1' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Fremdsprache 2' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Fremdsprache 3' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Fremdsprache 4' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Profil 1' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Profil 2' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
                    ), 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new LayoutAspect( 'Neigungskurs' ),
                        '&nbsp;'
                    ), 3 ),
                    new LayoutColumn( array(
                        new LayoutAspect( 'Jahre' ),
                        '&nbsp;'
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
