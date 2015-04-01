<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Education
 */
class Setup extends AbstractFrontend
{

    /**
     * @param null|array $Term
     * @param null|array $Level
     * @param null|array $Group
     * @param null|array $Subject
     *
     * @return Stage
     */
    public static function stageSetup( $Term, $Level, $Group, $Subject )
    {

        $View = new Stage();
        $View->setTitle( 'Grunddaten' );

        $tblSubject = Management::serviceEducation()->entitySubjectAll();
        if (!empty( $tblSubject )) {
            array_walk( $tblSubject, function ( TblSubject &$tblSubject ) {

                /** @noinspection PhpUndefinedFieldInspection */
                $tblSubject->Option = ( new Danger( 'Deaktivieren',
                    '/Sphere/Management/Education/Setup/Subject', new RemoveIcon(), array(
                        'tblSubject'  => $tblSubject->getId(),
                        'ActiveState' => 0
                    )
                ) )->__toString();
            } );
        }

        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Schulhalbjahr', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityTermAll(), null, array(
                                'Name'     => 'Name',
                                'DateFrom' => 'Vom',
                                'DateTo'   => 'Bis',
                            ) ),
                            Management::serviceEducation()->executeCreateTerm(
                                new Form(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Term[Name]', 'Name', 'Name' )
                                                , 4 ),
                                            new FormColumn(
                                                new DatePicker( 'Term[DateFrom]', 'Von', 'Von', new TimeIcon() )
                                                , 4 ),
                                            new FormColumn(
                                                new DatePicker( 'Term[DateTo]', 'Bis', 'Bis', new TimeIcon() )
                                                , 4 )
                                        ) ),
                                        new FormRow( array(
                                            new FormColumn(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Term )
                        ) ),
                    ) ),
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Klassenstufen', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityLevelAll(), null, array(
                                'Name' => 'Name',
                                'Description' => 'Beschreibung',
                            ), false ),
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
                                'Name'        => 'Name',
                                'Description' => 'Beschreibung',
                            ), false ),
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
                                        new FormRow( array(
                                            new FormColumn(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Subject )
                        ) )
                    )
                ) )
            )
        );

        return $View;
    }
}
