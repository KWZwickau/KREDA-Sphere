<?php
namespace KREDA\Sphere\Application\Management\Frontend\Period;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\InfoIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class SchoolYear
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Period
 */
class SchoolYear extends AbstractFrontend
{

    /**
     * @return Layout
     */
    public static function layoutSchoolYear()
    {

        $tblTermList = Management::serviceEducation()->entityTermAll();

        if (!empty( $tblTermList )) {

            usort( $tblTermList, function ( TblTerm $ObjectA, TblTerm $ObjectB ) {

                $PriorityA = strcmp(
                    $ObjectA->getServiceManagementCourse()->getName(),
                    $ObjectB->getServiceManagementCourse()->getName()
                );

                if (!$PriorityA) {
                    $PriorityA = ( $ObjectA->getFirstDateFrom() < $ObjectA->getFirstDateTo() ? 1 : -1 );
                }

                return $PriorityA;
            } );

            array_walk( $tblTermList, function ( TblTerm &$tblTerm ) {

                $tblTerm = new LayoutColumn(
                    new LayoutPanel(
                        $tblTerm->getServiceManagementCourse()->getName()
                        .new Muted( $tblTerm->getServiceManagementCourse()->getDescription() ),
                        array(
                            $tblTerm->getName(),
                            '1.HJ: '.$tblTerm->getFirstDateFrom().' - '.$tblTerm->getFirstDateTo(),
                            '2.HJ: '.$tblTerm->getSecondDateFrom().' - '.$tblTerm->getSecondDateTo(),
                        )
                        , LayoutPanel::PANEL_TYPE_DEFAULT,
                        new ButtonGroup( array(
                            new Primary(
                                '', '/Sphere/Management/Period/SchoolYear/Edit', new EditIcon(),
                                array( 'Id' => $tblTerm->getId() ), 'Bearbeiten'
                            ),
                            new Danger(
                                '', '/Sphere/Management/Period/SchoolYear/Destroy', new RemoveIcon(),
                                array( 'Id' => $tblTerm->getId() ), 'Löschen'
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
        if (!empty( $tblTermList )) {
            foreach ((array)$tblTermList as $tblTerm) {
                if ($LayoutRowCount % 4 == 0) {
                    $LayoutRow = new LayoutRow( array() );
                    $LayoutRowList[] = $LayoutRow;
                }
                $LayoutRow->addColumn( $tblTerm );
                $LayoutRowCount++;
            }
        } else {
            $LayoutRowList[] = new LayoutRow( new LayoutColumn(
                new Warning( 'Keine Schuljahre vorhanden', new InfoIcon() )
            ) );
        }
        return new Layout( new LayoutGroup( $LayoutRowList, new LayoutTitle( 'Schuljahre' ) ) );
    }
}
