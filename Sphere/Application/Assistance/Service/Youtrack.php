<?php
namespace KREDA\Sphere\Application\Assistance\Service;

use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Youtrack
 *
 * @package KREDA\Sphere\Application\Assistance\Service
 */
class Youtrack extends AbstractService
{

    /**
     * @param \KREDA\Sphere\Client\Frontend\Form\AbstractType $Ticket
     * @param null|string  $TicketSubject
     * @param null|string  $TicketMessage
     *
     * @return \KREDA\Sphere\Client\Frontend\Message\AbstractType|\KREDA\Sphere\Client\Frontend\Form\AbstractType
     */
    public function executeCreateTicket( AbstractType &$Ticket, $TicketSubject, $TicketMessage )
    {

        $Error = false;
        if (empty( $TicketSubject ) && null !== $TicketSubject) {
            $Ticket->setError( 'TicketSubject', 'Bitte geben Sie ein Thema ein' );
            $Error = true;
        } elseif (null === $TicketSubject) {
            $Error = true;
        } else {
            $Ticket->setSuccess( 'TicketSubject', '' );
        }
        if (empty( $TicketMessage ) && null !== $TicketMessage) {
            $Ticket->setError( 'TicketMessage', 'Bitte geben Sie ein Mitteilung ein' );
            $Error = true;
        } elseif (null === $TicketMessage) {
            $Error = true;
        } else {
            $Ticket->setSuccess( 'TicketMessage', '' );
        }

        if ($Error) {
            /**
             * Nothing to do
             */
            try {
                $Youtrack = new \KREDA\Sphere\Common\Youtrack\Youtrack();
                $Ticket->prependGridGroup( $Youtrack->ticketCurrent() );
                return $Ticket;
            } catch( \Exception $E ) {
                return new Danger( 'Das Support-System konnten nicht geladen werden' );
            }
        } else {
            /**
             * Submit Ticket
             */
            try {
                $Youtrack = new \KREDA\Sphere\Common\Youtrack\Youtrack();
                $Youtrack->ticketCreate( $TicketSubject, $TicketMessage );
                return new Success( 'Das Problem wurde erfolgreich dem Support mitgeteilt' );
            } catch( \Exception $E ) {
                return new Danger( 'Das Problem konnte nicht übertragen werden' );
            }
        }
    }

}
