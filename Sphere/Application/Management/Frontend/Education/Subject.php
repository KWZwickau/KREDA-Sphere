<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EnableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Link\Success;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Subject
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Education
 */
class Subject extends AbstractFrontend
{

    /**
     * @return Layout
     */
    public static function layoutSubject()
    {

        $tblSubjectList = Management::serviceEducation()->entitySubjectAll();

        if (!empty( $tblSubjectList )) {

            uasort( $tblSubjectList, function ( TblSubject $ObjectA, TblSubject $ObjectB ) {

                return strnatcasecmp( $ObjectA->getName(), $ObjectB->getName() );

            } );

            array_walk( $tblSubjectList, function ( TblSubject &$tblSubject ) {

                $tblCategoryList = Management::serviceEducation()->entityCategoryAllBySubject( $tblSubject );

                if (!empty( $tblCategoryList )) {
                    foreach ($tblCategoryList as $Index => $tblCategory) {
                        $tblCategoryList[$Index] = $tblCategory->getName();
                    }
                } else {
                    $tblCategoryList = array();
                }

                $tblSubject = new LayoutColumn(
                    new LayoutPanel(
                        $tblSubject->getAcronym(),
                        array_merge( array(
                            preg_replace( '!^(.*?)\s*/\s*(.*?)$!is', '${1} / ${2}', $tblSubject->getName() )
                        ), $tblCategoryList ),
                        ( $tblSubject->getActiveState()
                            ? LayoutPanel::PANEL_TYPE_SUCCESS
                            : LayoutPanel::PANEL_TYPE_DANGER
                        ),
                        new ButtonGroup( array(
                            new Primary(
                                'Bearbeiten', '/Sphere/Management/Education/Subject/Edit', new PencilIcon(),
                                array( 'Id' => $tblSubject->getId() )
                            ),
                            ( $tblSubject->getActiveState()
                                ? new Danger(
                                    'Deaktivieren', '/Sphere/Management/Education/Subject/Disable', new DisableIcon(),
                                    array( 'Id' => $tblSubject->getId() )
                                )
                                : new Success(
                                    'Aktivieren', '/Sphere/Management/Education/Subject/Enable', new EnableIcon(),
                                    array( 'Id' => $tblSubject->getId() )
                                )
                            ),
                        ) )
                    ), 4 );
            } );
        } else {
            $tblSubjectList = array(
                new LayoutColumn(
                    new Warning( 'Keine Fächer hinterlegt', new WarningIcon() )
                )
            );
        }

        $LayoutRowList = array();
        $LayoutRowCount = 0;
        $LayoutRow = null;
        /**
         * @var LayoutColumn $tblSubject
         */
        foreach ($tblSubjectList as $tblSubject) {
            if ($LayoutRowCount % 3 == 0) {
                $LayoutRow = new LayoutRow( array() );
                $LayoutRowList[] = $LayoutRow;
            }
            $LayoutRow->addColumn( $tblSubject );
            $LayoutRowCount++;
        }

        return new Layout(
            new LayoutGroup( $LayoutRowList, new LayoutTitle( 'Verfügbare Fächer' ) )
        );
    }
}
