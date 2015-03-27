<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\AbstractEntity;
use KREDA\Sphere\Common\Frontend\Table\AbstractTable;

/**
 * Class TableDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class TableVertical extends AbstractTable
{

    /**
     * @param AbstractEntity[] $DataList
     * @param GridTableTitle   $Title
     * @param array            $ColumnDefinition
     */
    function __construct(
        $DataList,
        GridTableTitle $Title = null,
        $ColumnDefinition = array()
    ) {

        if (!is_array( $DataList )) {
            $DataList = array( $DataList );
        }

        /** @var GridTableRow[] $DataList */
        array_walk( $DataList, function ( &$Row ) {

            array_walk( $Row, function ( &$Column, $Index ) {

                if (!is_object( $Column ) || !$Column instanceof GridTableCol) {
                    if ($Index == 0) {
                        $Column = new GridTableCol( $Column, 1, '1%' );
                    } else {
                        $Column = new GridTableCol( $Column );
                    }
                }
            } );
            // Convert to Array
            if (is_object( $Row )) {
                /** @var AbstractEntity $Row */
                $Row = array_filter( $Row->__toArray() );
            } else {
                $Row = array_filter( $Row );
            }
            /** @noinspection PhpParamsInspection */
            $Row = new GridTableRow( $Row );
        } );

        $this->GridBodyList = array( new GridTableBody( $DataList ) );

        $this->Template = $this->extensionTemplate( __DIR__.'/TableVertical.twig' );
        $this->Template->setVariable( 'Title', $Title );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridBodyList', $this->GridBodyList );
        return $this->Template->getContent();
    }

}
