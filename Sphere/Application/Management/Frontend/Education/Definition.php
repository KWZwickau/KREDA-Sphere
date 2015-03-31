<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Layout\Type\Column;
use KREDA\Sphere\Client\Frontend\Layout\Type\Grid;
use KREDA\Sphere\Client\Frontend\Layout\Type\Group;
use KREDA\Sphere\Client\Frontend\Layout\Type\Row;
use KREDA\Sphere\Client\Frontend\Layout\Type\Title;
use KREDA\Sphere\Client\Frontend\Text\Type\Danger;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Client\Frontend\Text\Type\Primary as PrimaryText;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCheckBox;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Definition
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Education
 */
class Definition extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageDefinition()
    {

        $View = new Stage();
        $View->setTitle( 'Klassenerstellung' );

        $tblSubjectGroup = Management::serviceEducation()->entitySubjectGroupAll();
        if (!empty( $tblSubjectGroup )) {
            array_walk( $tblSubjectGroup, function ( TblSubjectGroup &$tblSubjectGroup ) {

                $tblSubjectGroup->displayTerm =
                    new Danger( $tblSubjectGroup->getTblTerm()->getName() )
                    .new Muted(
                        $tblSubjectGroup->getTblTerm()->getDateFrom()
                        .' - '.$tblSubjectGroup->getTblTerm()->getDateTo()
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
        if (!empty( $tblLevel )) {
            array_walk( $tblLevel, function ( TblLevel &$tblLevel ) {

                $tblLevel->Selector = new InputCheckBox( 'SubjectGroup[Level]['.$tblLevel->getId().']' );
            } );
        }

        $tblGroup = Management::serviceEducation()->entityGroupAll();
        if (!empty( $tblGroup )) {
            array_walk( $tblGroup, function ( TblGroup &$tblGroup ) {

                $tblGroup->Selector = new InputCheckBox( 'SubjectGroup[Group]['.$tblGroup->getId().']' );
            } );
        }

        $tblSubject = Management::serviceEducation()->entitySubjectAll();
        if (!empty( $tblSubject )) {
            array_walk( $tblSubject, function ( TblSubject &$tblSubject ) {

                $tblSubject->Selector = new InputCheckBox( 'SubjectGroup[Subject]['.$tblSubject->getId().']' );
            } );
        }

        $View->setContent(
            new Grid(
                new Group( array(
                    new Row( array(
                        new Column( array(
                            new Title( 'Verfügbare Fach-Klassen', 'Kombinationen' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new Title( 'Fach-Klasse', 'Hinzufügen' ),
                            new FormDefault(
                                new GridFormGroup( array(
                                    new GridFormRow( array(
                                        new GridFormCol(
                                            new InputSelect( 'SubjectGroup[Term]', 'Zeitraum', array(
                                                'Name' => Management::serviceEducation()->entityTermAll()
                                            ) )
                                            , 6 ),
                                    ) ),
                                    new GridFormRow( array(
                                        new GridFormCol(
                                            new TableData( $tblLevel, null, array(
                                                'Selector' => new Primary( 'Alle auswählen', '' ),
                                                'Name'     => 'Klassenstufe',
                                            ), false )
                                            , 3 ),
                                        new GridFormCol(
                                            new TableData( $tblGroup, null, array(
                                                'Selector' => '',
                                                'Name'     => 'Name',
                                            ), false )
                                            , 3 ),
                                        new GridFormCol(
                                            new TableData( $tblSubject, null, array(
                                                'Selector' => '',
                                                'Acronym'  => 'Kürzel',
                                                'Name'     => 'Name',
                                            ), false )
                                            , 6 ),
                                    ) ),
                                    new GridFormRow( array(
                                        new GridFormCol(
                                            new SubmitPrimary( 'Hinzufügen' )
                                        )
                                    ) )
                                ) )
                            )
                        ) )

                    ) )
                ) )
            )
        );

        return $View;
    }
}
