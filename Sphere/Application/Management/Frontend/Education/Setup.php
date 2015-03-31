<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Layout\Type\Column;
use KREDA\Sphere\Client\Frontend\Layout\Type\Grid;
use KREDA\Sphere\Client\Frontend\Layout\Type\Group;
use KREDA\Sphere\Client\Frontend\Layout\Type\Row;
use KREDA\Sphere\Client\Frontend\Layout\Type\Title;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
            new Grid(
                new Group( array(
                    new Row( array(
                        new Column( array(
                            new Title( 'Schulhalbjahr', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityTermAll(), null, array(
                                'Name'     => 'Name',
                                'DateFrom' => 'Vom',
                                'DateTo'   => 'Bis',
                            ) ),
                            Management::serviceEducation()->executeCreateTerm(
                                new FormDefault(
                                    new GridFormGroup( array(
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new InputText( 'Term[Name]', 'Name', 'Name' )
                                                , 4 ),
                                            new GridFormCol(
                                                new InputDate( 'Term[DateFrom]', 'Von', 'Von', new TimeIcon() )
                                                , 4 ),
                                            new GridFormCol(
                                                new InputDate( 'Term[DateTo]', 'Bis', 'Bis', new TimeIcon() )
                                                , 4 )
                                        ) ),
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Term )
                        ) ),
                    ) ),
                    new Row( array(
                        new Column( array(
                            new Title( 'Klassenstufen', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityLevelAll(), null, array(
                                'Name' => 'Name',
                                'Description' => 'Beschreibung',
                            ), false ),
                            Management::serviceEducation()->executeCreateLevel(
                                new FormDefault(
                                    new GridFormGroup( array(
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new InputText( 'Level[Name]', 'Name', 'Name' )
                                                , 3 ),
                                            new GridFormCol(
                                                new InputText( 'Level[Description]', 'Beschreibung', 'Beschreibung' )
                                                , 9 )
                                        ) ),
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Level )
                        ), 6 ),
                        new Column( array(
                            new Title( 'Klassengruppen', 'Hinzufügen' ),
                            new TableData( Management::serviceEducation()->entityGroupAll(), null, array(
                                'Name'        => 'Name',
                                'Description' => 'Beschreibung',
                            ), false ),
                            Management::serviceEducation()->executeCreateGroup(
                                new FormDefault(
                                    new GridFormGroup( array(
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new InputText( 'Group[Name]', 'Name', 'Name' )
                                                , 3 ),
                                            new GridFormCol(
                                                new InputText( 'Group[Description]', 'Beschreibung', 'Beschreibung' )
                                                , 9 )
                                        ) ),
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new SubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Group )
                        ), 6 )
                    ) ),
                    new Row(
                        new Column( array(
                            new Title( 'Fächer', 'Hinzufügen/Deaktivieren' ),
                            new TableData( $tblSubject, null, array(
                                'Acronym' => 'Kürzel',
                                'Name'    => 'Name',
                                'Option'  => 'Option'
                            ) ),
                            Management::serviceEducation()->executeCreateSubject(
                                new FormDefault(
                                    new GridFormGroup( array(
                                        new GridFormRow( array(
                                            new GridFormCol(
                                                new InputText( 'Subject[Acronym]', 'Kürzel', 'Kürzel' )
                                                , 3 ),
                                            new GridFormCol(
                                                new InputText( 'Subject[Name]', 'Name', 'Name' )
                                                , 9 )
                                        ) ),
                                        new GridFormRow( array(
                                            new GridFormCol(
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
