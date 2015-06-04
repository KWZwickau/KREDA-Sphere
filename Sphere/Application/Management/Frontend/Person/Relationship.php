<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TransferIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Relationship
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Relationship extends AbstractFrontend
{

    /**
     * @param TblPerson $tblPerson
     * @param bool      $hasRemove
     *
     * @return Layout
     */
    public static function layoutRelationship( TblPerson $tblPerson, $hasRemove = false )
    {

        $tblRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );

        if (!empty( $tblRelationshipList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblRelationshipList, function ( TblPersonRelationshipList &$tblRelationship, $Index, $Data ) {

                /** @noinspection PhpUndefinedMethodInspection */
                if ($tblRelationship->getTblPersonA()->getId() == $Data[1]->getId()) {
                    $tblPerson = $tblRelationship->getTblPersonB();
                } else {
                    $tblPerson = $tblRelationship->getTblPersonA();
                }

                if ($tblRelationship->getTblPersonRelationshipType()->getName() == 'Sorgeberechtigt') {
                    $PanelType = LayoutPanel::PANEL_TYPE_WARNING;
                } else {
                    $PanelType = LayoutPanel::PANEL_TYPE_DEFAULT;
                }

                /** @var bool[]|TblPerson[] $Data */
                $tblRelationship = new LayoutColumn(
                    new LayoutPanel(
                        new TransferIcon().' Beziehung',
                        array(
                            $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName(),
                            'Personentyp: '.$tblPerson->getTblPersonType()->getName(),
                            'Beziehungstyp: '.$tblRelationship->getTblPersonRelationshipType()->getName(),
                        )
                        , $PanelType,
                        new ButtonGroup( array(
                            new Primary(
                                'Öffnen', '/Sphere/Management/Person/Edit', new PersonIcon(),
                                array( 'Id' => $tblPerson->getId() )
                            ),
                            ( $Data[0]
                                ? new Danger(
                                    'Löschen', '/Sphere/Management/Person/Relationship/Destroy', new RemoveIcon(),
                                    array( 'Id' => $Data[1]->getId(), 'Relationship' => $tblRelationship->getId() )
                                )
                                : null
                            )
                        ) )
                    ), 3 );
            }, array( $hasRemove, $tblPerson ) );
        } else {
            $tblRelationshipList = array(
                new LayoutColumn(
                    new Warning( 'Keine Beziehungen hinterlegt', new WarningIcon() )
                )
            );
        }

        return new Layout(
            new LayoutGroup( new LayoutRow( $tblRelationshipList ), new LayoutTitle( 'Beziehungen' ) )
        );
    }

    /**
     * @param int  $Id
     * @param int  $Link
     * @param bool $Confirm
     *
     * @return Stage
     */
    public static function stageDestroy( $Id, $Link, $Confirm = false )
    {

        $View = new Stage();
        $View->setTitle( 'Beziehung' );
        $View->setDescription( 'Löschen' );

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );
        $tblPersonRelationship = Management::servicePerson()->entityPersonRelationshipById( $Link );

        if ($tblPerson->getId() == $tblPersonRelationship->getTblPersonA()->getId()) {
            $tblPersonLink = $tblPersonRelationship->getTblPersonB();
        } else {
            $tblPersonLink = $tblPersonRelationship->getTblPersonA();
        }

        if (!$Confirm) {
            $View->setContent(
                new Layout(
                    new LayoutGroup( array(
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() ),
                                new Warning(
                                    'Wollen Sie die Beziehung ['.
                                    $tblPersonRelationship->getTblPersonRelationshipType()->getName()
                                    .' zu '.
                                    $tblPersonLink->getFullName()
                                    .'] wirklich löschen?',
                                    new QuestionIcon()
                                )
                            ) )
                        ),
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Danger(
                                    'Ja', '/Sphere/Management/Person/Relationship/Destroy', new OkIcon(),
                                    array( 'Id' => $Id, 'Link' => $Link, 'Confirm' => true )
                                ),
                                new Primary(
                                    'Nein', '/Sphere/Management/Person/Relationship/Edit', new DisableIcon(),
                                    array( 'Id' => $Id )
                                )
                            ) )
                        )
                    ) )
                )
            );
        } else {
            Management::servicePerson()->executeRemoveRelationship(
                $tblPersonRelationship->getTblPersonA()->getId(),
                $tblPersonRelationship->getTblPersonB()->getId(),
                $tblPersonRelationship->getTblPersonRelationshipType()->getId()
            );
            $View->setContent(
                new Layout( new LayoutGroup( array(
                    new LayoutRow(
                        new LayoutColumn(
                            new Success(
                                'Die Beziehung wurde gelöscht'
                            )
                        )
                    ),
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Redirect( '/Sphere/Management/Person/Relationship/Edit', 0,
                                array( 'Id' => $Id )
                            )
                        ) )
                    )
                ) ) ) );
        }
        return $View;
    }
}
