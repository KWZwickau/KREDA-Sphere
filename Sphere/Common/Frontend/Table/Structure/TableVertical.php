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
        array_walk( $DataList, function ( &$R ) {

            array_walk( $R, function ( &$V, $I ) {

                if (!is_object( $V ) || !$V instanceof GridTableCol) {
                    if ($I == 0) {
                        $V = new GridTableCol( $V, 1, '1%' );
                    } else {
                        $V = new GridTableCol( $V );
                    }
                }
            } );
            // Convert to Array
            if (is_object( $R )) {
                /** @var AbstractEntity $R */
                $R = array_filter( $R->__toArray() );
            } else {
                $R = array_filter( $R );
            }
            /** @noinspection PhpParamsInspection */
            $R = new GridTableRow( $R );
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
