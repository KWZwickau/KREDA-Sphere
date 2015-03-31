<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCheckBox;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
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
use KREDA\Sphere\Common\Frontend\Text\Element\TextDanger;
use KREDA\Sphere\Common\Frontend\Text\Element\TextMuted;
use KREDA\Sphere\Common\Frontend\Text\Element\TextPrimary;

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
                    new TextDanger( $tblSubjectGroup->getTblTerm()->getName() )
                    .new TextMuted(
                        $tblSubjectGroup->getTblTerm()->getDateFrom()
                        .' - '.$tblSubjectGroup->getTblTerm()->getDateTo()
                    );

                $tblSubjectGroup->displayLevel =
                    new TextPrimary( $tblSubjectGroup->getTblLevel()->getName() )
                    .new TextMuted( $tblSubjectGroup->getTblLevel()->getDescription() );

                $tblSubjectGroup->displayGroup =
                    new TextPrimary( $tblSubjectGroup->getTblGroup()->getName() )
                    .new TextMuted( $tblSubjectGroup->getTblGroup()->getDescription() );

                $tblSubjectGroup->displaySubject =
                    new TextPrimary( $tblSubjectGroup->getTblSubject()->getAcronym() )
                    .new TextMuted( $tblSubjectGroup->getTblSubject()->getName() );

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
            new GridLayout(
                new GridLayoutGroup( array(
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Verfügbare Fach-Klassen', 'Kombinationen' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new GridLayoutTitle( 'Fach-Klasse', 'Hinzufügen' ),
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
                                                'Selector' => '',
                                                'Name'     => 'Name',
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
                                            new ButtonSubmitPrimary( 'Hinzufügen' )
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
