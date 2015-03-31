<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkDanger;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayout;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutCol;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
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
                $tblSubject->Option = ( new ButtonLinkDanger( 'Deaktivieren',
                    '/Sphere/Management/Education/Setup/Subject', new RemoveIcon(), array(
                        'tblSubject'  => $tblSubject->getId(),
                        'ActiveState' => 0
                    )
                ) )->__toString();
            } );
        }

        $View->setContent(
            new GridLayout(
                new GridLayoutGroup( array(
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Schulhalbjahr', 'Hinzufügen' ),
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
                                                new ButtonSubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Term )
                        ) ),
                    ) ),
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Klassenstufen', 'Hinzufügen' ),
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
                                                new ButtonSubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Level )
                        ), 6 ),
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Klassengruppen', 'Hinzufügen' ),
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
                                                new ButtonSubmitPrimary( 'Hinzufügen' )
                                            )
                                        ) )
                                    ) )
                                ), $Group )
                        ), 6 )
                    ) ),
                    new GridLayoutRow(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Fächer', 'Hinzufügen/Deaktivieren' ),
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
                                                new ButtonSubmitPrimary( 'Hinzufügen' )
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
