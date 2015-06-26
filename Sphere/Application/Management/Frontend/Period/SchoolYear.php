<?php
namespace KREDA\Sphere\Application\Management\Frontend\Period;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
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

                self::extensionDebugger()->screenDump(
                    ( new \DateTime( $ObjectA->getFirstDateFrom() ) )->diff(
                        new \DateTime( $ObjectB->getFirstDateFrom() )
                    )
                );

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
                        new Primary(
                            '', '/Sphere/Management/Period/SchoolYear/Edit', new EditIcon(),
                            array( 'Id' => $tblTerm->getId() )
                        )
                    ), 3 );
            } );
        }

        $LayoutRowList = array();
        $LayoutRowCount = 0;
        $LayoutRow = null;
        /**
         * @var LayoutColumn $tblTerm
         */
        foreach ($tblTermList as $tblTerm) {
            if ($LayoutRowCount % 4 == 0) {
                $LayoutRow = new LayoutRow( array() );
                $LayoutRowList[] = $LayoutRow;
            }
            $LayoutRow->addColumn( $tblTerm );
            $LayoutRowCount++;
        }

        return new Layout( new LayoutGroup( $LayoutRowList, new LayoutTitle( 'Schuljahre' ) ) );
    }
}
