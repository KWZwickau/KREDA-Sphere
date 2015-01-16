<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Table\Structure;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class TableFromData
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Table
 */
class TableFromData extends TableDefault
{

    /**
     * @param AbstractEntity[] $DataList
     * @param string           $Title
     * @param bool|array       $Interactive
     * @param array            $ShowCol
     */
    function __construct( $DataList, $Title = '', $Interactive = true, $ShowCol = array() )
    {

        if (!is_array( $DataList )) {
            $DataList = array( $DataList );
        }
        if (empty( $ShowCol ) && !empty( $DataList )) {
            /** @var AbstractEntity[] $DataList */
            $GridHead = array_keys( $DataList[0]->__toArray() );
        } elseif (!empty( $ShowCol )) {
            $GridHead = $ShowCol;
        } else {
            $GridHead = array();

        }

        array_walk( $GridHead, function ( &$V ) {

            $V = new GridTableCol( $V );
        } );

        /** @var GridTableRow[] $DataList */
        array_walk( $DataList, function ( &$R, $I, $C ) {

            array_walk( $R, function ( &$V, $I, $C ) {

                if (empty( $C )) {
                    $V = new GridTableCol( $V );
                } elseif (in_array( preg_replace( '!^[^a-z0-9]*!is', '', $I ), $C )) {
                    $V = new GridTableCol( $V );
                } else {
                    $V = false;
                }
            }, $C );
            /** @var AbstractEntity $R */
            $R = array_filter( $R->__toArray() );
            /** @noinspection PhpParamsInspection */
            $R = new GridTableRow( $R );
        }, $ShowCol );

        parent::__construct(
            new GridTableHead( new GridTableRow( $GridHead ) ), new GridTableBody( $DataList ), $Title, $Interactive,
            null
        );
    }

}
