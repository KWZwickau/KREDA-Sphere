<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextArea;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Support
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Support
 */
class Support extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @param $TicketSubject
     * @param $TicketMessage
     *
     * @return Stage
     */
    public static function stageTicket( $TicketSubject, $TicketMessage )
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setDescription( 'Ticket erstellen' );
        $View->setMessage( '' );
        $View->setContent( Assistance::serviceYoutrack()->executeCreateTicket(
            new Form( array(
                new FormGroup(
                    new FormRow(
                        new FormColumn(
                            new TextField(
                                'TicketSubject', 'Thema', 'Thema'
                            )
                        )
                    ), new FormTitle( 'Problembeschreibung' )
                ),
                new FormGroup( array(
                        new FormRow(
                            new FormColumn(
                                new TextArea(
                                    'TicketMessage', 'Mitteilung', 'Mitteilung'
                                )
                            )
                        ),
                        new FormRow(
                            new FormColumn( array(
                                new Warning(
                                    'Bitte teilen Sie uns so genau wie möglich mit wie es zu diesem Problem kam'
                                ),
                                new Danger(
                                    'Sollte Ihr Problem bereits gemeldet worden sein, eröffnen Sie bitte kein neues Ticket'
                                )
                            ) )
                        )
                    )
                )
            ), new SubmitPrimary( 'Ticket eröffnen' )
            ), $TicketSubject, $TicketMessage
        ) );
        return $View;
    }

}
