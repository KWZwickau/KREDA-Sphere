<?php
namespace KREDA\Sphere\Application\System\Frontend\Protocol;

use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
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
    public static function stageLive()
    {

        $View = new Stage();
        $View->setTitle( 'Protokoll' );
        $View->setDescription( 'Live' );
        $View->setMessage( '' );

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
            $DataOrigin = unserialize( $V->getEntityFrom() );
            $DataCommit = unserialize( $V->getEntityTo() );

            if ($DataOrigin && $DataCommit) {
                $Data = $DataOrigin->__toArray();
                array_walk( $Data, function ( &$V, $I ) {

                    $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                } );
                $TableOrigin = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataOrigin ) ), 2 )
                    ) ), new GridTableBody( $Data )
                );
                $Data = $DataCommit->__toArray();
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
                $Data = $DataOrigin->__toArray();
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
                $Data = $DataCommit->__toArray();
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
            $View->setContent( new TableData( $tblProtocolList ) );
        }

        return $View;
    }
}
