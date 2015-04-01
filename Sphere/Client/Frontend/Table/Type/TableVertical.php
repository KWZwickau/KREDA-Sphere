<?php
namespace KREDA\Sphere\Client\Frontend\Table\Type;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableColumn;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableRow;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class TableVertical
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class TableVertical extends AbstractTable
{

    /**
     * @param AbstractEntity[] $DataList
     * @param TableTitle       $TableTitle
     * @param array            $ColumnDefinition
     */
    function __construct(
        $DataList,
        TableTitle $TableTitle = null,
        $ColumnDefinition = array()
    ) {

        if (!is_array( $DataList )) {
            $DataList = array( $DataList );
        }

        /** @var TableRow[] $DataList */
        array_walk( $DataList, function ( &$Row ) {

            array_walk( $Row, function ( &$Column, $Index ) {

                if (!is_object( $Column ) || !$Column instanceof TableColumn) {
                    if ($Index == 0) {
                        $Column = new TableColumn( $Column, 1, '1%' );
                    } else {
                        $Column = new TableColumn( $Column );
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
            $Row = new TableRow( $Row );
        } );

        $this->TableRow = array( new TableBody( $DataList ) );

        $this->Template = $this->extensionTemplate( __DIR__.'/TableVertical.twig' );
        $this->Template->setVariable( 'TableTitle', $TableTitle );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'BodyList', $this->TableRow );
        return $this->Template->getContent();
    }

}
