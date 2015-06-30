<?php
namespace KREDA\Sphere\Application\Management\Frontend\Group;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\InfoIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Group
 */
class Group extends AbstractFrontend
{

    /**
     * @param null|array $Group
     *
     * @return Stage
     */
    public static function stageGroup( $Group )
    {

        $View = new Stage();
        $View->setTitle( 'Gruppen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt alle vorhandenen Gruppen an' );

        $Form = self::formGroup();
        $Form->appendFormButton( new SubmitPrimary( 'Hinzufügen' ) );

        $View->setContent(
            self::layoutGroup()
            .new Layout( new LayoutGroup( new LayoutRow( new LayoutColumn(
                Management::serviceGroup()->executeCreateGroup( $Form, $Group )
            ) ), new LayoutTitle( 'Gruppe hinzufügen' ) ) )
        );

        return $View;
    }

    /**
     * @return Form
     */
    public static function formGroup()
    {

        return new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'Group[Name]', 'Name', 'Name', new GroupIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'Group[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon() )
                        , 4 ),
                ) ),
            ) )
        );
    }

    /**
     * @return Layout
     */
    public static function layoutGroup()
    {

        $tblGroupList = Management::serviceGroup()->fetchGroupAll();

        if (!empty( $tblGroupList )) {

            usort( $tblGroupList, function ( TblGroup $ObjectA, TblGroup $ObjectB ) {

                $PriorityA = strcmp(
                    $ObjectA->getIsEditable(),
                    $ObjectB->getIsEditable()
                );
                if (!$PriorityA) {
                    $PriorityA = strcmp(
                        $ObjectA->getName(),
                        $ObjectB->getName()
                    );
                }
                return $PriorityA;
            } );

            array_walk( $tblGroupList, function ( TblGroup &$tblGroup ) {

                $tblGroup = new LayoutColumn(
                    new LayoutPanel(
                        $tblGroup->getName(),
                        array(
                            ( $tblGroup->getDescription() ? $tblGroup->getDescription() : '' ),
                            'Anzahl Personen: '.Management::serviceGroup()->countPersonAllByGroup( $tblGroup ),
                            'Anzahl Firmen: '.Management::serviceGroup()->countCompanyAllByGroup( $tblGroup )
                        )
                        ,
                        ( !$tblGroup->getIsEditable() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT ),
                        new ButtonGroup( array(
                            new Primary(
                                '', '/Sphere/Management/Group/Edit', new EditIcon(),
                                array( 'Id' => $tblGroup->getId() ), 'Bearbeiten'
                            ),
                            new Primary(
                                '', '/Sphere/Management/Group/Member', new GroupIcon(),
                                array( 'Id' => $tblGroup->getId() ), 'Mitglieder'
                            ),
                            ( $tblGroup->getIsEditable()
                                ? new Danger(
                                    '', '/Sphere/Management/Group/Destroy', new RemoveIcon(),
                                    array( 'Id' => $tblGroup->getId() ), 'Löschen'
                                )
                                : null
                            )
                        ) )
                    ), 3 );
            } );
        }

        $LayoutRowList = array();
        $LayoutRowCount = 0;
        $LayoutRow = null;
        /**
         * @var LayoutColumn $tblTerm
         */
        if (!empty( $tblGroupList )) {
            foreach ((array)$tblGroupList as $tblTerm) {
                if ($LayoutRowCount % 4 == 0) {
                    $LayoutRow = new LayoutRow( array() );
                    $LayoutRowList[] = $LayoutRow;
                }
                $LayoutRow->addColumn( $tblTerm );
                $LayoutRowCount++;
            }
        } else {
            $LayoutRowList[] = new LayoutRow( new LayoutColumn(
                new Warning( 'Keine Gruppen vorhanden', new InfoIcon() )
            ) );
        }
        return new Layout( new LayoutGroup( $LayoutRowList, new LayoutTitle( 'Gruppen' ) ) );
    }
}
