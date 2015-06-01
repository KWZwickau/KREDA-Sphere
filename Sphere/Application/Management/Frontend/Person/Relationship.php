<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
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
     * @param bool      $hasOption
     *
     * @return Layout
     */
    public static function layoutRelationship( TblPerson $tblPerson, $hasOption = true )
    {

        $tblPersonRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );
        if (!empty( $tblPersonRelationshipList )) {
            array_walk( $tblPersonRelationshipList,
                function ( TblPersonRelationshipList &$tblPersonRelationship, $Index, TblPerson $tblPerson ) {

                    if ($tblPersonRelationship->getTblPersonA()->getId() == $tblPerson->getId()) {
                        $tblPersonRelationship->Person = $tblPersonRelationship->getTblPersonB()->getFullName().' ('.$tblPersonRelationship->getTblPersonB()->getTblPersonType()->getName().')';
                        $tblPersonRelationship->Relationship = $tblPersonRelationship->getTblPersonRelationshipType()->getName();
                    } else {
                        $tblPersonRelationship->Person = $tblPersonRelationship->getTblPersonA()->getFullName().' ('.$tblPersonRelationship->getTblPersonB()->getTblPersonType()->getName().')';
                        $tblPersonRelationship->Relationship = $tblPersonRelationship->getTblPersonRelationshipType()->getName();
                    }
                }, $tblPerson );

            if ($hasOption) {
                array_walk( $tblPersonRelationshipList,
                    function ( TblPersonRelationshipList &$tblPersonRelationship, $Index, TblPerson $tblPerson ) {

                        $tblPersonRelationship->Option = new Danger( 'Entfernen',
                            '/Sphere/Management/Person/Relationship/Destroy', new RemoveIcon(), array(
                                'Id'   => $tblPerson->getId(),
                                'Link' => $tblPersonRelationship->getId()
                            ) );
                    }, $tblPerson );
            }
        }
        return new Layout(
            new LayoutGroup(
                new LayoutRow(
                    new LayoutColumn( array(
                        ( $hasOption
                            ? new TableData( $tblPersonRelationshipList, null, array(
                                'Relationship' => 'Beziehung',
                                'Person'       => 'Person',
                                'Option'       => 'Optionen',
                            ) )

                            : new TableData( $tblPersonRelationshipList, null, array(
                                'Relationship' => 'Beziehung',
                                'Person'       => 'Person'
                            ) )
                        ),
                        new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship/Edit', new PencilIcon(),
                            array( 'Id' => $tblPerson->getId() )
                        )
                    ) )
                ), new LayoutTitle( 'Beziehungen' )
            )
        );
    }

    public static function stageCreate( $Id, $Relationship )
    {

        $View = new Stage( 'Beziehung', 'Hinzufügen' );
        $View->setContent( '' );
        return $View;
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
