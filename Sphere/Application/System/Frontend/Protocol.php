<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableDefault;

/**
 * Class Protocol
 *
 * @package KREDA\Sphere\Application\System\Frontend\Protocol
 */
class Protocol extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Protokoll' );
        $View->setDescription( 'Status' );

        /** @var TblProtocol[] $tblProtocolList */
        $tblProtocolList = System::serviceProtocol()->entityProtocolAll();

        array_walk( $tblProtocolList, function ( TblProtocol &$V ) {

            $Editor = new TableDefault(
                new GridTableHead( new GridTableRow(
                    new GridTableCol( '', 2 )
                ) ),
                new GridTableBody( array(
                    new GridTableRow( array(
                        new GridTableCol( 'Database' ),
                        new GridTableCol( $V->getProtocolDatabase() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Consumer' ),
                        new GridTableCol( $V->getConsumerName().' '.$V->getConsumerSuffix() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Login' ),
                        new GridTableCol( $V->getAccountUsername() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Person' ),
                        new GridTableCol( $V->getPersonFirstName().' '.$V->getPersonLastName() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Time' ),
                        new GridTableCol( date( 'd.m.Y H:i:s', $V->getProtocolTimestamp() ) )
                    ) )
                ) )
            );
            $DataOrigin = Protocol::fixObject( unserialize( $V->getEntityFrom() ) );
            $DataCommit = Protocol::fixObject( unserialize( $V->getEntityTo() ) );

            if ($DataOrigin && $DataCommit) {
                $Data = (array)$DataOrigin;
                array_walk( $Data, function ( &$V, $I ) {

                    $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                } );
                $TableOrigin = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataOrigin ) ), 2 )
                    ) ), new GridTableBody( $Data )
                );
                $Data = (array)$DataCommit;
                array_walk( $Data, function ( &$V, $I ) {

                    $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                } );
                $TableCommit = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataCommit ) ), 2 )
                    ) ), new GridTableBody( $Data )
                );

                $V = array(
                    'Id'     => $V->getId(),
                    'Editor' => $Editor,
                    'Origin' => $TableOrigin,
                    'Commit' => $TableCommit
                );
            } elseif ($DataOrigin) {
                $Data = (array)$DataOrigin;
                array_walk( $Data, function ( &$V, $I ) {

                    $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                } );
                $Table = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataOrigin ) ), 2 )
                    ) ), new GridTableBody( $Data )
                );
                $V = array(
                    'Id'     => $V->getId(),
                    'Editor' => $Editor,
                    'Origin' => $Table,
                    'Commit' => ''
                );
            } elseif ($DataCommit) {
                $Data = (array)$DataCommit;
                array_walk( $Data, function ( &$V, $I ) {

                    $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                } );
                $Table = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataCommit ) ), 2 )
                    ) ), new GridTableBody( $Data )
                );
                $V = array(
                    'Id'     => $V->getId(),
                    'Editor' => $Editor,
                    'Origin' => '',
                    'Commit' => $Table
                );
            }
        } );

        if (empty( $tblProtocolList )) {
            $View->setContent( new MessageWarning( 'Keine Daten vorhanden' ) );
        } else {
            $View->setContent( new TableData( $tblProtocolList, null, array(), array( 'responsive' => false ) ) );
        }

        return $View;
    }

    /**
     * Takes an __PHP_Incomplete_Class and casts it to a stdClass object.
     * All properties will be made public in this step.
     *
     * @since  1.1.0
     *
     * @param  object $object __PHP_Incomplete_Class
     *
     * @return object
     */
    private static function fixObject( $object )
    {

        if (!is_object( $object ) && gettype( $object ) == 'object') {
            // preg_replace_callback handler. Needed to calculate new key-length.
            $fix_key = create_function(
                '$matches',
                'return ":" . strlen( $matches[1] ) . ":\"" . $matches[1] . "\"";'
            );
            // 1. Serialize the object to a string.
            $dump = serialize( $object );
            // 2. Change class-type to 'stdClass'.
            preg_match( '/^O:\d+:"[^"]++"/', $dump, $match );
            $dump = preg_replace( '/^O:\d+:"[^"]++"/', 'O:8:"stdClass"', $dump );
            // 3. Make private and protected properties public.
            $dump = preg_replace_callback( '/:\d+:"\0.*?\0([^"]+)"/', $fix_key, $dump );
            // 4. Unserialize the modified object again.
            $dump = unserialize( $dump );
            $dump->ERROR = new MessageDanger( "Structure mismatch!<br/>".$match[0]."<br/>Please delete this Item" );
            return $dump;
        } else {
            return $object;
        }
    }
}
