<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Frontend\Period\SchoolYear;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Period
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Period extends AbstractFrontend
{

    /**
     * @param null|string $Name
     * @param null|array  $FirstTerm
     * @param null|array  $SecondTerm
     * @param null|int    $Course
     *
     * @return Stage
     */
    public static function stageSchoolYearCreate( $Name, $FirstTerm, $SecondTerm, $Course )
    {

        $View = new Stage();
        $View->setTitle( 'Zeiten' );
        $View->setDescription( 'Schuljahre' );

        $tblCourse = Management::serviceCourse()->entityCourseAll();

        $Form = new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'Name', 'Name', 'Name des Schuljahres',
                            new TimeIcon()
                        ), 8 ),
                    new FormColumn(
                        new SelectBox( 'Course', 'Bildungsgang', array( 'Name' => $tblCourse ),
                            new EducationIcon()
                        ), 4 ),
                ) ),
                new FormRow( array(
                    new FormColumn( array(
                        new Info( '1. Halbjahr' ),
                        new DatePicker( 'FirstTerm[DateFrom]', 'Von', 'Von',
                            new TimeIcon()
                        ),
                        new DatePicker( 'FirstTerm[DateTo]', 'Bis', 'Bis',
                            new TimeIcon()
                        )
                    ), 6 ),
                    new FormColumn( array(
                        new Info( '2. Halbjahr' ),
                        new DatePicker( 'SecondTerm[DateFrom]', 'Von', 'Von',
                            new TimeIcon()
                        ),
                        new DatePicker( 'SecondTerm[DateTo]', 'Bis', 'Bis',
                            new TimeIcon()
                        )
                    ), 6 ),
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new SubmitPrimary( 'Hinzufügen' )
                    )
                ) )
            ), new FormTitle( 'Schuljahr', 'Hinzufügen' ) )
        );

        $View->setContent(
            SchoolYear::layoutSchoolYear()
            .
            Management::serviceEducation()->executeCreateTerm( $Form, $Name, $FirstTerm, $SecondTerm, $Course )
        );
        return $View;
    }

    /**
     * @param null|int    $Id
     * @param null|string $Name
     * @param null|array  $FirstTerm
     * @param null|array  $SecondTerm
     * @param null|int    $Course
     *
     * @return Stage
     */
    public static function stageSchoolYearEdit( $Id, $Name, $FirstTerm, $SecondTerm, $Course )
    {

        $View = new Stage();
        $View->setTitle( 'Zeiten' );
        $View->setDescription( 'Schuljahre' );

        $tblTerm = Management::serviceEducation()->entityTermById( $Id );
        $tblCourse = Management::serviceCourse()->entityCourseAll();

        $Global = self::extensionSuperGlobal();
        if (!$Global->POST) {
            $Global->POST['Name'] = $tblTerm->getName();
            $Global->POST['Course'] = $tblTerm->getServiceManagementCourse()->getId();
            $Global->POST['FirstTerm']['DateFrom'] = $tblTerm->getFirstDateFrom();
            $Global->POST['FirstTerm']['DateTo'] = $tblTerm->getFirstDateTo();
            $Global->POST['SecondTerm']['DateFrom'] = $tblTerm->getSecondDateFrom();
            $Global->POST['SecondTerm']['DateTo'] = $tblTerm->getSecondDateTo();
            $Global->savePost();
        }

        $View->setContent(
            new Success(
                $tblTerm->getName()
                .' ('.$tblTerm->getServiceManagementCourse()->getName().')'
                .': '
                .$tblTerm->getFirstDateFrom().' - '.$tblTerm->getFirstDateTo()
                .', '
                .$tblTerm->getSecondDateFrom().' - '.$tblTerm->getSecondDateTo()
            )
            .Management::serviceEducation()->executeChangeTerm(
                new Form(
                    new FormGroup( array(
                        new FormRow( array(
                            new FormColumn(
                                new TextField( 'Name', 'Name', 'Name des Schuljahres',
                                    new TimeIcon()
                                ), 8 ),
                            new FormColumn(
                                new SelectBox( 'Course', 'Bildungsgang', array( 'Name' => $tblCourse ),
                                    new EducationIcon()
                                ), 4 ),
                        ) ),
                        new FormRow( array(
                            new FormColumn( array(
                                new Info( '1. Halbjahr' ),
                                new DatePicker( 'FirstTerm[DateFrom]', 'Von', 'Von',
                                    new TimeIcon()
                                ),
                                new DatePicker( 'FirstTerm[DateTo]', 'Bis', 'Bis',
                                    new TimeIcon()
                                )
                            ), 6 ),
                            new FormColumn( array(
                                new Info( '2. Halbjahr' ),
                                new DatePicker( 'SecondTerm[DateFrom]', 'Von', 'Von',
                                    new TimeIcon()
                                ),
                                new DatePicker( 'SecondTerm[DateTo]', 'Bis', 'Bis',
                                    new TimeIcon()
                                )
                            ), 6 ),
                        ) ),
                        new FormRow( array(
                            new FormColumn(
                                new SubmitPrimary( 'Änderungen speichern' )
                            )
                        ) )
                    ), new FormTitle( 'Schuljahr', 'Bearbeiten' ) )
                ), $Id, $Name, $FirstTerm, $SecondTerm, $Course )
        );
        return $View;
    }
}
