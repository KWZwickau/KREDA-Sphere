<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\CheckBox;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Client\Frontend\Text\Type\Danger;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Client\Frontend\Text\Type\Primary as PrimaryText;
use KREDA\Sphere\Common\AbstractFrontend;

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
        if (!empty( $tblLevel )) {
            array_walk( $tblLevel, function ( TblLevel &$tblLevel ) {

                $tblLevel->Selector = new CheckBox( 'SubjectGroup[Level]['.$tblLevel->getId().']' );
            } );
        }

        $tblGroup = Management::serviceEducation()->entityGroupAll();
        if (!empty( $tblGroup )) {
            array_walk( $tblGroup, function ( TblGroup &$tblGroup ) {

                $tblGroup->Selector = new CheckBox( 'SubjectGroup[Group]['.$tblGroup->getId().']' );
            } );
        }

        $tblSubject = Management::serviceEducation()->entitySubjectAll();
        if (!empty( $tblSubject )) {
            array_walk( $tblSubject, function ( TblSubject &$tblSubject ) {

                $tblSubject->Selector = new CheckBox( 'SubjectGroup[Subject]['.$tblSubject->getId().']' );
            } );
        }

        $tblTerm = Management::serviceEducation()->entityTermAll();
        if (!empty( $tblTerm )) {
            array_walk( $tblTerm, function ( TblTerm &$tblTerm ) {

                $tblTerm->Title = $tblTerm->getName().' '.$tblTerm->getFirstDateFrom().' - '.$tblTerm->getFirstDateTo();
            } );
        }

        $View->setContent(
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Bestehende Fach-Klassen', 'Kombinationen' ),
                            new TableData( $tblSubjectGroup, null, array(
                                'displayTerm'    => 'Zeitraum',
                                'displayLevel'   => 'Stufe',
                                'displayGroup'   => 'Gruppe',
                                'displaySubject' => 'Fach',
                            ) ),
                            new LayoutTitle( 'Fach-Klassen', 'aus Vorlage erstellen' ),
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new Warning( 'Von' )
                                            , 1 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Term]', 'Zeitraum', array(
                                                'Title' => $tblTerm
                                            ) )
                                            , 3 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Level]', 'Stufe', array(
                                                'Name' => Management::serviceEducation()->entityLevelAll()
                                            ) )
                                            , 1 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Group]', 'Gruppe', array(
                                                'Name' => Management::serviceEducation()->entityGroupAll()
                                            ) )
                                            , 1 ),
                                        new FormColumn(
                                            new Warning( 'Nach' )
                                            , 1 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Term]', 'Zeitraum', array(
                                                'Title' => $tblTerm
                                            ) )
                                            , 3 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Level]', 'Stufe', array(
                                                'Name' => Management::serviceEducation()->entityLevelAll()
                                            ) )
                                            , 1 ),
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Group]', 'Gruppe', array(
                                                'Name' => Management::serviceEducation()->entityGroupAll()
                                            ) )
                                            , 1 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new SubmitPrimary( 'Kopieren' )
                                        )
                                    ) )
                                ) )
                            ),
                            new LayoutTitle( 'Kombination', 'hinzufügen' ),
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new SelectBox( 'SubjectGroup[Term]', 'Zeitraum', array(
                                                'Name' => Management::serviceEducation()->entityTermAll()
                                            ) )
                                            , 6 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new TableData( $tblLevel, null, array(
                                                'Selector' => new Primary( 'Alle auswählen', '' ),
                                                'Name'     => 'Klassenstufe',
                                            ), false )
                                            , 3 ),
                                        new FormColumn(
                                            new TableData( $tblGroup, null, array(
                                                'Selector' => '',
                                                'Name'     => 'Name',
                                            ), false )
                                            , 3 ),
                                        new FormColumn(
                                            new TableData( $tblSubject, null, array(
                                                'Selector' => '',
                                                'Acronym'  => 'Kürzel',
                                                'Name'     => 'Name',
                                            ), false )
                                            , 6 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
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
