<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Layout\Type\Column;
use KREDA\Sphere\Client\Frontend\Layout\Type\Grid;
use KREDA\Sphere\Client\Frontend\Layout\Type\Group;
use KREDA\Sphere\Client\Frontend\Layout\Type\Row;
use KREDA\Sphere\Client\Frontend\Layout\Type\Title;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Relationship
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Relationship extends AbstractFrontend
{

    /**
     * @param int      $tblPerson
     * @param null|int $tblRelationship
     * @param null|int $tblRelationshipType
     * @param bool|int $Remove
     *
     * @return Stage
     */
    public static function stageRelationship( $tblPerson, $tblRelationship, $tblRelationshipType, $Remove = false )
    {

        $View = new Stage();
        $View->setTitle( 'Beziehungen' );
        $View->setDescription( 'Bearbeiten' );

        if (
            !empty( $tblPerson ) && !empty( $tblRelationship ) && !empty( $tblRelationshipType ) && !$Remove
            && $tblPerson != $tblRelationship
        ) {
            Management::servicePerson()->executeAddRelationship( $tblPerson, $tblRelationship, $tblRelationshipType );
        }
        if (!empty( $tblPerson ) && !empty( $Remove )) {
            $tblPersonRelationship = Management::servicePerson()->entityPersonRelationshipById( $Remove );
            if ($tblPersonRelationship) {
                Management::servicePerson()->executeRemoveRelationship(
                    $tblPersonRelationship->getTblPersonA()->getId(),
                    $tblPersonRelationship->getTblPersonB()->getId(),
                    $tblPersonRelationship->getTblPersonRelationshipType()->getId()
                );
            }
        }

        if (empty( $tblPerson )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $tblPerson );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {
                $tblPersonRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );
                $PersonRelationshipList = array();
                if ($tblPersonRelationshipList) {
                    /** @var TblPersonRelationshipList $tblPersonRelationship */
                    foreach ((array)$tblPersonRelationshipList as $tblPersonRelationship) {
                        if ($tblPersonRelationship->getTblPersonA()->getId() == $tblPerson->getId()) {
                            $PersonRelationshipList[] = array(
                                'Person'       => $tblPersonRelationship->getTblPersonB()->getFullName(),
                                'Relationship' => $tblPersonRelationship->getTblPersonRelationshipType()->getName(),
                                'Option' => new Danger( 'Entfernen',
                                    '/Sphere/Management/Person/Relationship', new ShareIcon(), array(
                                        'tblPerson' => $tblPerson->getId(),
                                        'Remove'    => $tblPersonRelationship->getId()
                                    ) )
                            );
                        } else {
                            $PersonRelationshipList[] = array(
                                'Person'       => $tblPersonRelationship->getTblPersonA()->getFullName(),
                                'Relationship' => $tblPersonRelationship->getTblPersonRelationshipType()->getName(),
                                'Option' => new Danger( 'Entfernen',
                                    '/Sphere/Management/Person/Relationship', new ShareIcon(), array(
                                        'tblPerson' => $tblPerson->getId(),
                                        'Remove'    => $tblPersonRelationship->getId()
                                    ) )
                            );
                        }
                    }
                }
                $View->setContent(
                    new Grid(
                        new Group( array(
                            new Row( array(
                                new Column( array(
                                    new Success(
                                        $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName()
                                    )
                                ) )
                            ) ),
                            new Row( array(
                                new Column( array(
                                    new Title( 'Personen', 'Zugewiesen' ),
                                    new TableData( $PersonRelationshipList, null, array(), false )
                                ), 5 ),
                                new Column( array(
                                    new Title( 'Personen', 'Suchen' ),
                                    new TableData(
                                        '/Sphere/Management/Table/PersonRelationship?tblPerson='.$tblPerson->getId()
                                        , null,
                                        array(
                                            'Id' => '#',
                                            'Name'   => 'Name',
                                            'Option' => 'Option'
                                        ),
                                        array(
                                            "lengthChange" => true,
                                            "lengthMenu"   => [ [ 5, 10 ], [ 5, 10 ] ],
                                            "pageLength" => 10,
                                            "columnDefs"   => array(
                                                array( "orderable" => false, "targets" => 0 ),
                                                array( "orderable" => false, "targets" => -1 )
                                            )
                                        )
                                    ),
                                    new Primary( 'Neue Person anlegen', '/Sphere/Management/Person/Create' )

                                ), 7 )
                            ) ),
                        ) )
                    )
                );
            }
        }
        return $View;
    }
}
