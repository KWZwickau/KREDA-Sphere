<?php
namespace KREDA\Sphere\Application\System\Frontend\Protocol;

use KREDA\Sphere\Application\Assistance\Frontend\Clarification\Cause\Warning;
use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

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
        $tblProtocolList = System::serviceProtocol()->entityProtocol();
        if (empty( $tblProtocolList )) {
            $View->setContent( new Warning( 'Keine Daten vorhanden' ) );
            return $View;
        }

        krsort( $tblProtocolList );
        $Content = '';
        foreach ($tblProtocolList as $tblProtocol) {
            $System = '<table class="table table-bordered table-condensed small" style="margin: auto;"><tbody>'
                .'<tr><th>Database</th><td>'.$tblProtocol->getProtocolDatabase().'</td></tr>'
                .'<tr><th>Consumer</th><td>'.$tblProtocol->getConsumerName().' '.$tblProtocol->getConsumerSuffix().'</td></tr>'
                .'<tr><th>Login</th><td>'.$tblProtocol->getAccountUsername().'</td></tr>'
                .'<tr><th>Person</th><td>'.$tblProtocol->getPersonFirstName().' '.$tblProtocol->getPersonLastName().'</td></tr>'
                .'<tr><th>Time</th><td>'.date( 'd.m.Y H:i:s', $tblProtocol->getProtocolTimestamp() ).'</td></tr>'
                .'</tbody></table>';
            $Left = '';
            $Right = '';
            $From = unserialize( $tblProtocol->getEntityFrom() );
            $To = unserialize( $tblProtocol->getEntityTo() );
            if ($From) {
                $Left = '<table class="table table-bordered table-condensed small" style="margin: auto;"><thead><tr><th colspan="2">'.get_class( $From ).'</th></tr></thead><tbody>';
                $From = $From->__toArray();
                foreach ($From as $Key => $Value) {
                    $Left .= '<tr><th>'.$Key.'</th><td>'.$Value.'</td></tr>';
                }
                $Left .= '</tbody></table>';
            }
            if ($To) {
                $Right = '<table class="table table-bordered table-condensed small" style="margin: auto;"><thead><tr><th colspan="2">'.get_class( $To ).'</th></tr></thead><tbody>';
                $To = $To->__toArray();
                foreach ($To as $Key => $Value) {
                    $Right .= '<tr><th>'.$Key.'</th><td>'.$Value.'</td></tr>';
                }
                $Right .= '</tbody></table>';
            }
            if ($Left && $Right) {
                $Content .= '<tr class="bg-info"><td>'.$tblProtocol->getId().'</td><td>'.$System.'</td><td>'.$Left.'</td><td>'.$Right.'</td></tr>';
            } else {
                if ($Left) {
                    $Content .= '<tr class="bg-danger"><td>'.$tblProtocol->getId().'</td><td>'.$System.'</td><td>'.$Left.'</td><td>'.$Right.'</td></tr>';
                } else {
                    if ($Right) {
                        $Content .= '<tr class="bg-success"><td>'.$tblProtocol->getId().'</td><td>'.$System.'</td><td>'.$Left.'</td><td>'.$Right.'</td></tr>';
                    }
                }
            }
        }

        $View->setContent( '<table id="TableProtocolList" class="table table-condensed table-bordered"><thead><tr><th>Id</th><th>System</th><th>From</th><th>To</th></tr></thead><tbody>'.$Content.'</tbody></table>
        <script>
            Client.Use( "ModTable", function()
                {
                    jQuery( "#TableProtocolList" ).ModTable();
                }
            );
        </script>
        ' );
        return $View;
    }
}
