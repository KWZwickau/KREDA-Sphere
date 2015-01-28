<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Element\InputTextArea;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;

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
            new FormDefault( array(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputText(
                                'TicketSubject', 'Thema', 'Thema'
                            )
                        )
                    ), 'Problembeschreibung'
                ),
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol(
                                new InputTextArea(
                                    'TicketMessage', 'Mitteilung', 'Mitteilung'
                                )
                            )
                        ),
                        new GridFormRow(
                            new GridFormCol( array(
                                new MessageWarning(
                                    'Bitte teilen Sie uns so genau wie möglich mit wie es zu diesem Problem kam'
                                ),
                                new MessageDanger(
                                    'Sollte Ihr Problem bereits gemeldet worden sein, eröffnen Sie bitte kein neues Ticket'
                                )
                            ) )
                        )
                    )
                )
            ), new ButtonSubmitPrimary( 'Ticket eröffnen' )
            ), $TicketSubject, $TicketMessage
        ) );
        return $View;
    }

}
