<?php
namespace KREDA\Sphere\Application\Assistance\Frontend\Support;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Application\Assistance\Frontend\Support\Youtrack\Ticket;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Support
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Support
 */
class Support extends AbstractFrontend
{

    /**
     * @param $TicketSubject
     * @param $TicketMessage
     *
     * @return Stage
     */
    static public function stageTicket( $TicketSubject, $TicketMessage )
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setDescription( 'Ticket erstellen' );
        $View->setMessage( '' );
        $View->setContent( Assistance::serviceYoutrack()->executeCreateTicket(
            new Ticket(), $TicketSubject, $TicketMessage
        ) );
        return $View;
    }

}
