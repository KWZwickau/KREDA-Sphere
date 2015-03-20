<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayout;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutCol;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
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
     *
     * @return Stage
     */
    public static function stageRelationship( $tblPerson, $tblRelationship, $tblRelationshipType )
    {

        $View = new Stage();
        $View->setTitle( 'Beziehungen' );
        $View->setDescription( 'Bearbeiten' );

        if (!empty( $tblPerson ) && !empty( $tblRelationship ) && !empty( $tblRelationshipType )) {
            Management::servicePerson()->executeAddRelationship( $tblPerson, $tblRelationship, $tblRelationshipType );
        }

        if (empty( $tblPerson )) {
            $View->setContent( new MessageWarning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $tblPerson );
            if (empty( $tblPerson )) {
                $View->setContent( new MessageWarning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {
                $tblPersonRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );

                $View->setContent(
                    new GridLayout(
                        new GridLayoutGroup( array(
                            new GridLayoutRow( array(
                                new GridLayoutCol( array(
                                    new MessageSuccess(
                                        $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName()
                                    )
                                ) )
                            ) ),
                            new GridLayoutRow( array(
                                new GridLayoutCol( array(
                                    new GridLayoutTitle( 'Personen', 'Zugewiesen' ),
                                    new TableData( $tblPersonRelationshipList, null, array(), false )
                                ), 5 ),
                                new GridLayoutCol( array(
                                    new GridLayoutTitle( 'Personen', 'Suchen' ),
                                    new TableData(
                                        '/Sphere/Management/REST/PersonListRelationship?tblPerson='.$tblPerson->getId()
                                        , null,
                                        array(
                                            'Id' => '#',
                                            'Name'   => 'Name',
                                            'Option' => 'Option'
                                        ),
                                        array(
                                            "lengthChange" => true,
                                            "lengthMenu"   => [ [ 5, 10 ], [ 5, 10 ] ],
                                            "pageLength"   => 5,
                                            "columnDefs"   => array(
                                                array( "orderable" => false, "targets" => 0 ),
                                                array( "orderable" => false, "targets" => -1 )
                                            )
                                        )
                                    ),
                                    new ButtonLinkPrimary( 'Neue Person anlegen', '/Sphere/Management/Person/Create' )

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
