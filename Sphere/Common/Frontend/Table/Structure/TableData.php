<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class TableData
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class TableData extends TableDefault
{

    /**
     * @param AbstractEntity[] $DataList
     * @param GridTableTitle   $Title
     * @param array            $ColumnDefinition
     * @param bool|array       $Interactive
     */
    function __construct( $DataList, GridTableTitle $Title = null, $ColumnDefinition = array(), $Interactive = true )
    {

        if (!is_array( $DataList )) {
            $DataList = array( $DataList );
        }
        if (empty( $ColumnDefinition ) && !empty( $DataList )) {
            if (is_object( current( $DataList ) )) {
                /** @var AbstractEntity[] $DataList */
                $GridHead = array_keys( $DataList[0]->__toArray() );
            } else {
                $GridHead = array_keys( $DataList[0] );
            }
        } elseif (!empty( $ColumnDefinition )) {
            // Rename by ShowCol
            $GridHead = array_values( $ColumnDefinition );
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
                } elseif (in_array( preg_replace( '!^[^a-z0-9_]*!is', '', $I ), array_keys( $C ) )) {
                    $V = new GridTableCol( $V );
                } else {
                    $V = false;
                }
            }, $C );
            // Convert to Array
            if (is_object( $R )) {
                /** @var AbstractEntity $R */
                $R = array_filter( $R->__toArray() );
            } else {
                $R = array_filter( $R );
            }
            /** @var array $R */
            // Sort by ShowCol
            $R = array_merge( array_flip( array_keys( $C ) ), $R );
            /** @noinspection PhpParamsInspection */
            $R = new GridTableRow( $R );
        }, $ColumnDefinition );

        if (count( $DataList ) > 0) {
            parent::__construct(
                new GridTableHead( new GridTableRow( $GridHead ) ), new GridTableBody( $DataList ), $Title,
                $Interactive, null
            );
        } else {
            parent::__construct(
                new GridTableHead( new GridTableRow( $GridHead ) ), new GridTableBody( $DataList ), $Title, false, null
            );
        }
    }

}
